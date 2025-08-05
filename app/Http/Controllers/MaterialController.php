<?php

namespace App\Http\Controllers;

use App\Models\ModelMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MaterialController extends Controller
{
    public function addMaterial(Request $request){
        ModelMaterial::create([
            'nama_material' => $request->material_name,
            'type'  => $request->type,
            'processing_time' => $request->processing_time
        ]);
    
        // Redirect atau response
        Session::flash('message', 'Material berhasil ditambahkan.');
        Session::flash('icon', 'success');
        return redirect()->back();
    }

    public function editMaterial(Request $request,$id){
        $data = ModelMaterial::find($id);
        $data->nama_material = $request->material_name;
        $data->type = $request->type;
        $data->processing_time = $request->processing_time;

        $data->save();

        Session::flash('message', 'Material berhasil diperbarui.');
        Session::flash('icon', 'success');
        return redirect()->back();
    }

    public function deleteMaterial($id){
        $data = ModelMaterial::find($id);
        $data->delete();

        return response()->json([
            'message' => 'Material berhasil dihapus.',
        ]);
    }

    public function getMaterial(){
        $dataMaterial = DB::table('wax_material')->get();
        return response()->json([
            'success' => true,
            'data' => $dataMaterial
        ]);

    }
}
