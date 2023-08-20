<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $all = Prospect::join('history_prospect as hp','hp.prospect_id','prospect.id')
                                ->join('pt','pt.id','hp.pt_id')
                                ->join('users','users.id','pt.user_id')
                                ->where('pt.user_id',Auth::user()->id)
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

        $total = HistoryProspect::leads()->count();
        $process = HistoryProspect::leads()
                ->whereBetween('prospect.status_id',[2, 4])
                ->count();
        $closing = HistoryProspect::leads()
                ->where('prospect.status_id',5)
                ->count();
        $notinterest = HistoryProspect::leads()
                ->where('prospect.status_id',6)
                ->count();
        // dd($closing,$notinterest);

        $get_project = Pt::with('project')
                        ->where('user_id',Auth::user()->id)
                        ->first()
                        ->project()
                        ->select('id','nama_project')
                        ->get();

        $data_prospect = [];

        foreach ($get_project as $key => $value) {

            $prospect = DB::table('history_prospect')
                        ->join('prospect', 'prospect.id', '=', 'history_prospect.prospect_id')
                        ->join('pt', 'pt.id', '=', 'history_prospect.pt_id')
                        ->join('users', 'users.id', '=', 'pt.user_id')
                        ->join('agent', 'agent.id', '=', 'history_prospect.agent_id')
                        ->where('history_prospect.project_id', '=', $value->id)
                        ->select(
                            DB::raw('COUNT(*) as total'),
                            DB::raw('SUM(CASE WHEN prospect.status_id = 1 THEN 1 ELSE 0 END) as total_new'),
                            DB::raw('SUM(CASE WHEN prospect.status_id IN (2, 3, 4) THEN 1 ELSE 0 END) as total_process'),
                            DB::raw('SUM(CASE WHEN prospect.status_id = 5 THEN 1 ELSE 0 END) as total_closing'),
                            DB::raw('SUM(CASE WHEN prospect.status_id = 6 THEN 1 ELSE 0 END) as total_not_interested'),
                            DB::raw('SUM(CASE WHEN prospect.status_id = 7 THEN 1 ELSE 0 END) as total_expired'),
                            'agent.nama_agent as agent_name'
                        )
                        ->groupBy('agent_id')
                        // ->where('users.id',Auth::user()->id)
                        ->get();
            $data_prospect[] = collect([
                'project' => $value->nama_project,
                'prospect' => $prospect
            ]);
        }

        // dd($data_prospect);

        // dd(
        //     // HistoryProspect::join('prospect','prospect.id','history_prospect.prospect_id')
        //     // ->join('pt','pt.id','history_prospect.pt_id')
        //     // ->join('users','users.id','pt.user_id')
        //     // ->join('agent','agent.id','history_prospect.agent_id')
        //     // ->selectRaw('count(*) as total, agent.nama_agent as agent_name')
        //     // // ->selectRaw('count(*) as total, users.name as agent_name')
        //     // ->groupBy('agent_id')
        //     // // ->where('users.id',Auth::user()->id)
        //     // // ->where('prospect.status_id',1)
        //     // // ->selectRaw('count(*) as closing, prospect.status_id as status')
        //     // ->where('history_prospect.project_id','=', 22)
        //     // ->get()

        //     // SELECT COUNT(*) as total, SUM(CASE WHEN prospect.status_id = 1 THEN 1 ELSE 0 END) as total_new, SUM(CASE WHEN prospect.status_id IN (2, 3, 4) THEN 1 ELSE 0 END) as total_process, SUM(CASE WHEN prospect.status_id = 5 THEN 1 ELSE 0 END) as total_closing, SUM(CASE WHEN prospect.status_id = 6 THEN 1 ELSE 0 END) as total_not_interest, SUM(CASE WHEN prospect.status_id = 7 THEN 1 ELSE 0 END) as total_expired, agent.nama_agent as agent_name FROM history_prospect JOIN prospect ON prospect.id = history_prospect.prospect_id JOIN pt ON pt.id = history_prospect.pt_id JOIN users ON users.id = pt.user_id JOIN agent ON agent.id = history_prospect.agent_id WHERE history_prospect.project_id = 22 GROUP BY agent_id;
        //     DB::table('history_prospect')
        //     ->join('prospect', 'prospect.id', '=', 'history_prospect.prospect_id')
        //     ->join('pt', 'pt.id', '=', 'history_prospect.pt_id')
        //     ->join('users', 'users.id', '=', 'pt.user_id')
        //     ->join('agent', 'agent.id', '=', 'history_prospect.agent_id')
        //     ->where('history_prospect.project_id', '=', 23)
        //     ->select(
        //         DB::raw('COUNT(*) as total'),
        //         DB::raw('SUM(CASE WHEN prospect.status_id = 1 THEN 1 ELSE 0 END) as total_new'),
        //         DB::raw('SUM(CASE WHEN prospect.status_id IN (2, 3, 4) THEN 1 ELSE 0 END) as total_process'),
        //         DB::raw('SUM(CASE WHEN prospect.status_id = 5 THEN 1 ELSE 0 END) as total_closing'),
        //         DB::raw('SUM(CASE WHEN prospect.status_id = 6 THEN 1 ELSE 0 END) as total_not_interest'),
        //         DB::raw('SUM(CASE WHEN prospect.status_id = 7 THEN 1 ELSE 0 END) as total_expired'),
        //         'agent.nama_agent as agent_name'
        //     )
        //     ->groupBy('agent_id')
        //     // ->where('users.id',Auth::user()->id)
        //     ->get()
        // );

        // count all history prospect group by project id where pt id = 9
        // $allProspectByProject = HistoryProspect::select('project_id',DB::raw('count(*) as total'))
        //                                 ->where('pt_id',9)
        //                                 ->groupBy('project_id')
        //                                 ->get();
        // $allProspectByProject = HistoryProspect::select('project_id',DB::raw('count(*) as total'))
        //                                         ->groupBy('project_id')
        //                                         ->get();
        // dd($allProspectByProject);

        $platformLeads = HistoryProspect::count_leads_by_src("Platform","Developer");
        $categoryPlatform = $platformLeads->pluck('nama_platform')->toArray();
        $countPlatform = $platformLeads->pluck('total')->toArray();

        $sourceLeads = HistoryProspect::count_leads_by_src("Source", "Developer");
        $categorySource= $sourceLeads->pluck('nama_sumber')->toArray();
        $countSource= $sourceLeads->pluck('total')->toArray();

        $historySales = HistorySales::SalesActivity()->take(10);

        return view('pages.dashboard.index',compact(
            'total',
            'process',
            'closing',
            'notinterest',
            'categoryPlatform',
            'countPlatform',
            'categorySource',
            'countSource',
            'historySales',
            'data_prospect'
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

        if($request->since){
            $summaryLabel = "since ".$request->since;
            $start_date = Carbon::createFromFormat('Y-m-d', $request->since);
            // Session::put('sinceDate', $start_date);
        }

        $end_date = Carbon::now();
        if ($request->since && $request->to) {
            // $start_date = Session::get('sinceDate');
            $start_date = Carbon::createFromFormat('Y-m-d', $request->since);
            $summaryLabel = "since ".$start_date." to ".$request->to;
            $end_date = Carbon::createFromFormat('Y-m-d', $request->to);
        }

        // Session::forget('since');

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

        $leads = HistoryProspect::leads()->whereBetween('prospect.created_at', [$start_date, $end_date]);

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
