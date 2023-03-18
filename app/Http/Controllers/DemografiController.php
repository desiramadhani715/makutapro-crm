<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use App\Models\HistoryProspect;

class DemografiController extends Controller
{
    public function getkota(Request $request){
        $kota = City::where('province_id',$request->province_id)->pluck('city','id');
        return response()->json($kota);
    }

    public function index(){

        $leadsByAge = HistoryProspect::count_leads_by_src("Age");
        $categoryAge= $leadsByAge->pluck('name')->toArray();

        $leadsByGender = HistoryProspect::count_leads_by_src("Gender");
        $categoryGender= $leadsByGender->pluck('name')->toArray();

        

        return view('pages.demografi.index', compact(
            'leadsByAge',
            'categoryAge',
            'leadsByGender',
            'categoryGender'
        ));
    }
}
