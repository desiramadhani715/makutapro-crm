<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prospect;
use App\Models\User;
use App\Models\Agent;
use App\Models\ProjectAgent;
use App\Models\Project;
use App\Models\Sales;
use App\Models\HistoryProspect;
use App\Models\HistoryBlast;
use App\Models\HistoryProspectMove;
use App\Models\HistoryInputSales;
use App\Models\HistorySales;
use App\Models\LogFirstProcess;
use App\Models\LeadsClosing;
use App\Models\LeadsNotInterested;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd('tes');
        // $prospect_cpy = Prospect::all();
        // for ($i=0; $i < count($prospect_cpy) ; $i++) { 
        //     $p = Prospectt::find($prospect_cpy[$i]->id);
        //         if($p->LevelInputID == 'system'){
        //             Prospect::where('id',$prospect_cpy[$i]->id)->update([
        //                 'role_by' => 4
        //             ]);
        //         }
        // }

        // $prospect_cpy = Prospect::all();
        // $prospect = Prospectt::all();
        // for ($i=0; $i < count($prospect) ; $i++) {
        //     for ($j=0; $j < count($prospect_cpy) ; $j++) { 
        //         if($prospect_cpy[$j]->id == $prospect[$i]->ProspectID){
        //             if($prospect[$i]->LevelInputID == 'system'){
        //                 Prospect::where('id',$prospect_cpy[$j]->id)->update([
        //                     'role_by' => 4
        //                 ]);
        //             }
        //         }
        //     } 
        // }
        // die;
        // dd(count($prospect));

        $all = Prospect::join('history_prospect as hp','hp.prospect_id','prospect.id')
                                ->join('pt','pt.id','hp.pt_id')
                                ->join('users','users.id','pt.user_id')
                                ->where('pt.user_id',Auth::user()->id)
                                // ->whereIn('prospect.role_by',[6,7])
                                ->select(DB::raw('count(*) as total_prospect'),DB::raw('YEAR(prospect.created_at) year, MONTHNAME(prospect.created_at) month, DAY(prospect.created_at) day'))
                                // ->whereRaw('prospect.created_at >= DATE_ADD(NOW(), INTERVAL -14 DAY)')
                                // ->take(14)
                                ->groupBy('year','month','day');

        // dd($all->get()); 
        $digitalSrc = $all->whereNotIn('prospect.role_by',[6,7])->get();
        $salesSrc = $all->whereIn('prospect.role_by',[6,7])->get();
        $totalDs = null;
        $totalSs = null;
                                
        foreach ($digitalSrc as $key => $value) {
            $totalDs[] = $value->total_prospect;
        }
        
        foreach ($salesSrc as $key => $value) {
            $totalSs[] = $value->total_prospect;
        }

        // dd($all->get(), $totalDs, $totalSs);

        $total = HistoryProspect::total_leads()->count();
        $process = HistoryProspect::total_leads()
                ->whereBetween('prospect.status_id',[2, 4])
                ->count();
        $closing = HistoryProspect::total_leads()
                ->where('prospect.status_id',5)
                ->count();
        $notinterest = HistoryProspect::total_leads()
                ->where('prospect.status_id',6)
                ->count();
        // dd($closing,$notinterest);

        return view('pages.dashboard.index',compact(
            'total','process','closing','notinterest'
        ));          
    }

    public function refreshChart(Request $request){
        
        if($request->days == 1)
            $summaryLabel = "Today";
        if($request->days == 7)
            $summaryLabel = "a Week ago";
        if($request->days == 30)
            $summaryLabel = "a Month ago";
        if($request->days == 365)
            $summaryLabel = "a Year ago";
            
        $start_date = Carbon::now()->subDays($request->days);
        $end_date = Carbon::now();

        $salesSource = HistoryProspect::CountLeadsByRole('Sales Source', $start_date, $end_date);
        $digSource = HistoryProspect::CountLeadsByRole('Digital Source', $start_date, $end_date);

        // dd($salesSource); source sales blm ke get
        
        $resultDigital = [];
        $resultSales = [];
        $dates = [];
        $countDigitalSource = [];
        $countSalesSource = [];
        
        $current_date = $start_date;
        while ($current_date->lte($end_date)) {
            $resultDigital[$current_date->format('Y-m-d')] = 0;
            $resultSales[$current_date->format('Y-m-d')] = 0;
            $current_date->addDay();
        }
        // dd($digSource);
        
        foreach ($digSource as $x) {
            $resultDigital[$x->date] = $x->total;
        }
        
        foreach ($salesSource as $x) {
            $resultSales[$x->date] = $x->total;
        }

        foreach ($resultDigital as $date => $total) {
            $dates[] = $date; //untuk mendapatkan tanggal
            $countDigitalSource[] = $total;
        }  

        foreach ($resultSales as $date => $total) {
            $countSalesSource[] = $total;
        }  

        $formattedDates = array_map(function ($date) {
            return Carbon::createFromFormat('Y-m-d', $date)->translatedFormat('d F Y');
        }, $dates);

        $start_date = Carbon::now()->subDays($request->days);
        $end_date = Carbon::now();

        $leads = HistoryProspect::total_leads()->whereBetween('prospect.created_at', [$start_date, $end_date]);

        $data = [
            "summaryLabel" => $summaryLabel,

            "total" => $leads->count(),
    
            "inProcess" => $leads->whereBetween('prospect.status_id',[2, 4])->count(),
    
            "closing" => $leads->where('prospect.status_id',5)->count(),
    
            "notInterest" => $leads->where('prospect.status_id',6)->count(),

            "dates" => $formattedDates,

            "countDigitalSource" => $countDigitalSource,

            "countSalesSource" => $countSalesSource,

            "digSource" => $digSource->count(),

            "salesSource" => $salesSource->count()
        ];

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}