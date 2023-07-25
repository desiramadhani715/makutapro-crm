<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roas;
use App\Models\Prospect;
use Carbon\Carbon;

class RoasController extends Controller
{
    public function index(){
        $roas = Roas::getRoas();

        return response()->json(['message' => 'Roas get successfully.', 'data' => $roas]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $google = str_replace('.', '', str_replace('Rp. ', '', $request->google));
        $sosmed = str_replace('.', '', str_replace('Rp. ', '', $request->sosmed));
        $detik = str_replace('.', '', str_replace('Rp. ', '', $request->detik));
        $received_budget = str_replace('.', '', str_replace('Rp. ', '', $request->received_budget));

        list($monthName, $tahun) = explode(" ", $request->month);
        // $bulan = date('n', strtotime($monthName));

        $lead = Prospect::whereRaw('month(prospect.created_at) = '.'"'.$monthName.'" && year(prospect.created_at) = '.'"'.$tahun.'"')
                ->with(['historyProspect' => function ($query) {
                    $query->where('project_id', $request->project_id);
                }]);

        $received_date = null;
        if($request->received_date){
            $received_date = Carbon::createFromFormat('m/d/Y', $request->received_date)->format('Y-m-d');
        }

        $roas = new Roas();
        $roas->project_id = $request->project_id;
        $roas->google = intval($google);
        $roas->sosmed = intval($sosmed);
        $roas->detik = intval($detik);
        $roas->cpl = $lead->count() == 0 ? 0 : round((intval($google) + intval($sosmed) + intval($detik)) / $lead->count() , 2);
        $roas->cpa = $lead->where('prospect.status_id', 5)->count() == 0 ? 0 : round((intval($google) + intval($sosmed) + intval($detik)) / $lead->count() , 2);
        $roas->bulan = $monthName;
        $roas->tahun = $tahun;
        $roas->received_budget = intval($received_budget);
        $roas->received_date = $received_date;
        $roas->save();

        return response()->json(['message' => 'Roas saved successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $roas = Roas::find($id);
        return response()->json(['data' => $roas]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Roas  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $google = str_replace('.', '', str_replace('Rp. ', '', $request->google));
        $sosmed = str_replace('.', '', str_replace('Rp. ', '', $request->sosmed));
        $detik = str_replace('.', '', str_replace('Rp. ', '', $request->detik));
        $received_budget = str_replace('.', '', str_replace('Rp. ', '', $request->received_budget));

        list($monthName, $tahun) = explode(" ", $request->month);
        // $bulan = date('n', strtotime($monthName));

        $lead = Prospect::whereRaw('month(prospect.created_at) = '.'"'.$monthName.'" && year(prospect.created_at) = '.'"'.$tahun.'"')
                ->with(['historyProspect' => function ($query) {
                    $query->where('project_id', $request->project_id);
                }]);

        $received_date = null;
        if($request->received_date){
            $received_date = Carbon::createFromFormat('m/d/Y', $request->received_date)->format('Y-m-d');
        }

        $roas = Roas::find($id);
        $roas->project_id = $request->project_id;
        $roas->google = intval($google);
        $roas->sosmed = intval($sosmed);
        $roas->detik = intval($detik);
        $roas->cpl = $lead->count() == 0 ? 0 : round((intval($google) + intval($sosmed) + intval($detik)) / $lead->count() , 2);
        $roas->cpa = $lead->where('prospect.status_id', 5)->count() == 0 ? 0 : round((intval($google) + intval($sosmed) + intval($detik)) / $lead->count() , 2);
        $roas->bulan = $monthName;
        $roas->tahun = $tahun;
        $roas->received_budget = intval($received_budget);
        $roas->received_date = $received_date;
        $roas->save();

        return response()->json(['msg' => 'Roas updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Roas  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = Roas::where('id', $id)->delete();
        return response()->json(['msg' => 'Roas Type deleted successfully']);
    }

}
