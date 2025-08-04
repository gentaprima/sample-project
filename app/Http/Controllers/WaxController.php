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
        // $id = $request->id;

        // $subProcess = ModelSubProcess::find($id);
        // if (!$subProcess) {
        //     return response()->json(['message' => 'Subprocess tidak ditemukan'], 404);
        // }

        // // 1. Ubah status subprocess sekarang ke DONE
        // $subProcess->status_subprocess = 'DONE';
        // $subProcess->save();

        // // 2. Cek apakah masih ada subprocess lain di proses ini yang belum DONE
        // $remainingSubProcess = ModelSubProcess::where('id_process', $subProcess->id_process)
        //     ->where('status_subprocess', '!=', 'DONE')
        //     ->count();

        // if ($remainingSubProcess === 0) {
        //     // a. Semua subprocess sudah DONE, ubah status proses jadi DONE
        //     $process = ModelProcess::find($subProcess->id_process);
        //     $process->status = 'DONE';
        //     $process->save();

        //     // b. Cari proses berikutnya
        //     $nextProcess = ModelProcess::where('id', '>', $process->id)->orderBy('id')->first();

        //     if ($nextProcess) {
        //         // Ubah status proses berikutnya jadi IN_PROCESS
        //         $nextProcess->status = 'IN_PROCESS';
        //         $nextProcess->save();

        //         // c. Ubah subprocess pertama dari proses berikutnya ke IN_PROCESS
        //         $firstSubProcess = ModelSubProcess::where('id_process', $nextProcess->id)
        //             ->orderBy('id') // kalau ada urutan lain pakai itu
        //             ->first();

        //         if ($firstSubProcess) {
        //             $firstSubProcess->status_subprocess = 'IN_PROCESS';
        //             $firstSubProcess->save();
        //         }
        //     }
        // } else {
        //     // Masih ada subprocess di proses sekarang → aktifkan subprocess berikutnya
        //     $nextSubProcess = ModelSubProcess::where('id_process', $subProcess->id_process)
        //         ->where('id', '>', $subProcess->id)
        //         ->where('status_subprocess', 'NOT_PROCESS')
        //         ->orderBy('id')
        //         ->first();

        //     if ($nextSubProcess) {
        //         $nextSubProcess->status_subprocess = 'IN_PROCESS';
        //         $nextSubProcess->save();
        //     }
        // }

        // return response()->json(
        //     [
        //         'message' => 'Status berhasil diperbarui dan subprocess berikutnya disiapkan.',
        //         'parent_id' => $subProcess->id_process
        //     ]
        // );

        $id = $request->id;

        // 1. Ambil subprocess yang sedang diubah
        $subProcess = ModelSubProcess::find($id);
        if (!$subProcess) {
            return response()->json(['message' => 'Subprocess tidak ditemukan'], 404);
        }

        // 2. Tandai subprocess ini DONE
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
}
