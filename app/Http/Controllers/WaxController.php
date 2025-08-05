<?php

namespace App\Http\Controllers;

use App\Models\ModelProcess;
use App\Models\ModelSubProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WaxController extends Controller
{
    public function processAdd(Request $request)
    {

        $data = $request->data['process'];
        $firstProcess = true;
        $firstSubprocessGlobal = true; // hanya satu subproses pertama yang IN_PROCESS


        // Cari kode produksi terakhir
        $lastProductionCode = DB::table('tbl_process')
            ->orderByDesc('id')
            ->value('production_code');

        if ($lastProductionCode) {
            // Ambil angka dari kode terakhir, misal: "BATCH-003" → 3
            $lastNumber = (int) Str::after($lastProductionCode, 'BATCH-');
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // Format kode baru, misal: "BATCH-004"
        $productionCode = 'BATCH-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        foreach ($data as $process) {
            $partName = "WAX ROOM";
            $processName = $process['process_name'];
            $amount = 1;
            $amountOfficer = 0;
            $totalMenit = 0;

            // Hitung total pekerja dan waktu
            foreach ($process['detail'] as $detail) {
                $subKey = array_key_first($detail);
                $sub = $detail[$subKey];

                $amountOfficer += count($sub['nama_pekerja']);
                $totalMenit += is_numeric($sub['waktu_pengerjaan']) ? (int)$sub['waktu_pengerjaan'] : 0;
            }

            // Status untuk proses (proses pertama = IN_PROCESS)
            $status = $firstProcess ? 'IN_PROCESS' : 'NOT_PROCESS';

            $processId = DB::table('tbl_process')->insertGetId([
                'part_name'      => $partName,
                'process_name'   => $processName,
                'amount'         => $amount,
                'officer_amount' => $amountOfficer,
                'total_time'     => $totalMenit,
                'status'         => $status,
                'production_code'  => $productionCode, // ⬅️ tambahkan ini
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            foreach ($process['detail'] as $detail) {
                $subKey = array_key_first($detail);
                $sub = $detail[$subKey];

                // Subproses pertama saja yang IN_PROCESS
                $subStatus = $firstSubprocessGlobal ? 'IN_PROCESS' : 'NOT_PROCESS';
                $firstSubprocessGlobal = false;

                DB::table('tbl_subprocess')->insert([
                    'subprocess_name'   => $sub['nama_sub_process'],
                    'material_name'     => implode(', ', $sub['material']),
                    'material_results'  => $sub['hasil_material'],
                    'qty'               => is_numeric($sub['qty']) ? (int)$sub['qty'] : 0,
                    'processing_time'   => is_numeric($sub['waktu_pengerjaan']) ? (int)$sub['waktu_pengerjaan'] : 0,
                    'officer_name'      => implode(', ', $sub['nama_pekerja']),
                    'group_process'     => $processName,
                    'id_process'        => $processId,
                    'status_subprocess' => $subStatus,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }

            $firstProcess = false;
        }

        return response()->json([
            'success' => true,
            'message' => "Process successfully added"
        ]);
    }

    public function getDataSub($id)
    {
        $data = DB::table('tbl_subprocess')->where('id_process', '=', $id)->get();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getDataProcess()
    {
        $data = DB::table('tbl_process')->get();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function changeStatus(Request $request)
    {
        $id = $request->id;

        // 1. Ambil subprocess yang sedang diubah
        $subProcess = ModelSubProcess::find($id);
        if (!$subProcess) {
            return response()->json(['message' => 'Subprocess tidak ditemukan'], 404);
        }

        // 2. Update stock material sebelum menandai DONE
        $this->updateMaterialStock($subProcess);

        // 3. Tandai subprocess ini DONE
        $subProcess->status_subprocess = 'DONE';
        $subProcess->save();

        // 3. Ambil proses utama
        $currentProcess = ModelProcess::find($subProcess->id_process);

        // 4. Cek apakah semua subprocess pada proses ini sudah DONE
        $remainingSubProcess = ModelSubProcess::where('id_process', $subProcess->id_process)
            ->where('status_subprocess', '!=', 'DONE')
            ->count();

        if ($remainingSubProcess === 0) {
            // Semua subprocess sudah DONE → set proses ini DONE
            $currentProcess->status = 'DONE';
            $currentProcess->save();

            // Cek proses selanjutnya DALAM jalur produksi yang sama
            $nextProcess = ModelProcess::where('production_code', $currentProcess->production_code)
                ->where('id', '>', $currentProcess->id)
                ->where('status', 'NOT_PROCESS')
                ->orderBy('id')
                ->first();

            if ($nextProcess) {
                $nextProcess->status = 'IN_PROCESS';
                $nextProcess->save();

                // Aktifkan subprocess pertama dari proses selanjutnya
                $firstSubProcess = ModelSubProcess::where('id_process', $nextProcess->id)
                    ->orderBy('id')
                    ->first();

                if ($firstSubProcess) {
                    $firstSubProcess->status_subprocess = 'IN_PROCESS';
                    $firstSubProcess->save();
                }
            }
        } else {
            // Masih ada subprocess lain di proses ini → aktifkan subprocess berikutnya
            $nextSubProcess = ModelSubProcess::where('id_process', $subProcess->id_process)
                ->where('id', '>', $subProcess->id)
                ->where('status_subprocess', 'NOT_PROCESS')
                ->orderBy('id')
                ->first();

            if ($nextSubProcess) {
                $nextSubProcess->status_subprocess = 'IN_PROCESS';
                $nextSubProcess->save();
            }
        }

        return response()->json([
            'message' => 'Status berhasil diperbarui.',
            'parent_id' => $subProcess->id_process
        ]);
    }

    /**
     * Update stock material berdasarkan subprocess yang selesai
     */
    private function updateMaterialStock($subProcess)
    {
        try {
            // 1. Kurangi stock material yang digunakan (material_name)
            if (!empty($subProcess->material_name)) {
                $materials = explode(', ', $subProcess->material_name);
                $qty = $subProcess->qty;

                foreach ($materials as $materialName) {
                    $materialName = trim($materialName);
                    if (!empty($materialName)) {
                        // Cari material dengan type 'finished'
                        $material = DB::table('wax_material')
                            ->where('nama_material', $materialName)
                            ->where('type', 'finished')
                            ->first();

                        if ($material) {
                            // Kurangi stock
                            $newStock = max(0, $material->stock - $qty);
                            DB::table('wax_material')
                                ->where('id', $material->id)
                                ->update(['stock' => $newStock]);

                            Log::info("Stock material {$materialName} berkurang: {$material->stock} → {$newStock} (qty: {$qty})");
                        }
                    }
                }
            }

            // 2. Tambah stock material hasil (material_results)
            if (!empty($subProcess->material_results)) {
                $resultMaterial = trim($subProcess->material_results);
                $qty = $subProcess->qty;

                if (!empty($resultMaterial)) {
                    // Cari atau buat material hasil
                    $material = DB::table('wax_material')
                        ->where('nama_material', $resultMaterial)
                        ->first();

                    if ($material) {
                        // Update stock yang ada
                        $newStock = $material->stock + $qty;
                        DB::table('wax_material')
                            ->where('id', $material->id)
                            ->update(['stock' => $newStock]);

                        Log::info("Stock material hasil {$resultMaterial} bertambah: {$material->stock} → {$newStock} (qty: {$qty})");
                    } else {
                        // Buat material baru jika belum ada
                        DB::table('wax_material')->insert([
                            'nama_material' => $resultMaterial,
                            'type' => 'finished',
                            'stock' => $qty,
                            'processing_time' => 0, // Default processing time
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        Log::info("Material baru dibuat: {$resultMaterial} dengan stock: {$qty}");
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Error updating material stock: " . $e->getMessage());
        }
    }
}
