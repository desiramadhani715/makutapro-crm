<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class DemografiController extends Controller
{
    public function getkota(Request $request){
        $kota = City::where('province_id',$request->province_id)->pluck('city','id');
        return response()->json($kota);
    }

    public function index(){
        return view('pages.demografi.index');
    }
}
