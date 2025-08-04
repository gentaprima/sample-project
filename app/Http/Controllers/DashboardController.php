<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request){
        return view("dashboard");
    }

    public function getMaterial(Request $request){
        $dataMaterial = DB::table('wax_material')->get();
        $data['dataMaterial'] = $dataMaterial;
        return view('material',$data);
    }

    public function getWaxRoom(Request $request){
        $dataWax = DB::table('tbl_process')->get();
        $data['dataWax'] = $dataWax;
        return view('wax-room',$data);
    }

    public function addWaxRoom(Request $request){
        return view('form-add-wax-room');
    }
}
