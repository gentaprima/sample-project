<?php

namespace App\Http\Controllers;

use App\Models\ModelMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MaterialController extends Controller
{
    public function addMaterial(Request $request){
        ModelMaterial::create([
            'nama_material' => $request->material_name,
            'type'  => $request->type
        ]);
    
        // Redirect atau response
        return redirect()->back()->with('success', 'Material berhasil ditambahkan!');
    }

    public function editMaterial(Request $request,$id){
        $data = ModelMaterial::find($id);
        $data->nama_material = $request->material_name;
        $data->type = $request->type;

        $data->save();

        Session::flash('message', 'Material berhasil diperbarui.');
        Session::flash('icon', 'success');
        return redirect()->back();
    }
}
