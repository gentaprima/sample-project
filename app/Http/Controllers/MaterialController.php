<?php

namespace App\Http\Controllers;

use App\Models\ModelMaterial;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function addMaterial(Request $request){
        ModelMaterial::create([
            'nama_material' => $request->material_name
        ]);
    
        // Redirect atau response
        return redirect()->back()->with('success', 'Material berhasil ditambahkan!');
    }
}
