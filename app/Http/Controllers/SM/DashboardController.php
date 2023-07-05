<?php

namespace App\Http\Controllers\SM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prospect;
use App\Models\User;
use App\Models\Pt;
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
use App\Helper\Helper;

class DashboardController extends Controller
{
    public function index(Request $request){
        // dd(Pt::with('user')->where('user_id',Auth::user()->agent->user_id)->get());
        $all = Prospect::join('history_prospect as hp','hp.prospect_id','prospect.id')
                        ->join('agent','agent.id','hp.agent_id')
                        ->where('agent.user_id',Auth::user()->id)
                        ->select(DB::raw('count(*) as total_prospect'),DB::raw('YEAR(prospect.created_at) year, MONTHNAME(prospect.created_at) month, DAY(prospect.created_at) day'))
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
        $leads = Prospect::join('history_prospect as hp','hp.prospect_id','prospect.id')->join('agent','agent.id','hp.agent_id')->where('agent.user_id', Auth::user()->id);
        $total = $leads->count();
        $process = $leads
                ->whereBetween('prospect.status_id',[2, 4])
                ->count();
        $closing = $leads
                ->where('prospect.status_id',5)
                ->count();
        $notinterest = $leads
                ->where('prospect.status_id',6)
                ->count();
        // dd($closing,$notinterest);


        $platformLeads = HistoryProspect::count_leads_by_src("Platform", "Agent");
        $categoryPlatform = $platformLeads->pluck('nama_platform')->toArray();
        $countPlatform = $platformLeads->pluck('total')->toArray();

        $sourceLeads = HistoryProspect::count_leads_by_src("Source", "Agent");
        $categorySource= $sourceLeads->pluck('nama_sumber')->toArray();
        $countSource= $sourceLeads->pluck('total')->toArray();

        $historySales = HistorySales::SalesActivity()->take(10);

        return view('SM.dashboard.index',compact(
            'total',
            'process',
            'closing',
            'notinterest',
            'categoryPlatform',
            'countPlatform',
            'categorySource',
            'countSource',
            'historySales'
        ));


    }

    public function loadLeadsChart(Request $request){

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

        $salesSource = HistoryProspect::count_leads_by_role('Sales Source', $start_date, $end_date);
        $digSource = HistoryProspect::count_leads_by_role('Digital Source', $start_date, $end_date);

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

        $leads = Prospect::join('history_prospect as hp','hp.prospect_id','prospect.id')->join('agent','agent.id','hp.agent_id')->where('agent.user_id', Auth::user()->id)
                                ->whereBetween('prospect.created_at', [$start_date, $end_date]);

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
}
