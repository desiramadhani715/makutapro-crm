<?php

namespace App\Http\Controllers\SM;

use App\Http\Controllers\Controller;
use App\Models\Prospect;
use App\Models\Project;
use App\Models\HistoryProspect;
use App\Models\HistoryProspectMove;
use App\Models\HistoryChangeStatus;
use App\Models\HistorySales;
use App\Models\HistoryInputSales;
use App\Models\HistoryBlast;
use App\Models\Standard;
use App\Models\City;
use App\Models\ClosingLeads;
use App\Models\User;
use App\Models\Agent;
use App\Models\Sales;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Helper\Helper;
use Carbon\Carbon;
use \stdClass;

class ProspectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = Prospect::join('history_prospect as hp','hp.prospect_id','prospect.id')->join('agent','agent.id','hp.agent_id')->where('agent.user_id', Auth::user()->id)->get();
        $project = Project::get_project()->get();
        $platform = DB::table('sumber_platform')->get();
        $source = DB::table('sumber_data')->get();
        $status = DB::table('status')->get();

        return view('SM.prospect.index',compact('project','platform','source','status'));
    }

    public function get_all(Request $request){

        $query = HistoryProspect::all_leads()->where('prospect.nama_prospect','like','%'.$request->search['value'].'%');

        if($request->project != ""){
            $query = $query->where('history_prospect.project_id','=',$request->project);
        }
        if($request->agent != ""){
            $query = $query->where('history_prospect.agent_id','=',$request->agent);
        }
        if($request->sales != ""){
            $query = $query->where('history_prospect.sales_id','=',$request->sales);
        }
        if($request->platform != ""){
            $query = $query->where('prospect.sumber_platform_id','=',$request->platform);
        }
        if($request->source != ""){
            $query = $query->where('prospect.sumber_data_id','=',$request->source);
        }
        if($request->status != ""){
            $query = $query->where('prospect.status_id','=',$request->status);
        }
        if($request->role != ""){
            $query = $query->where('prospect.role_by','=',$request->role);
        }
        if($request->since != ""){
            $query = $query->where('prospect.created_at','>=',$request->since);
        }
        if($request->to != ""){
            $query = $query->where('prospect.created_at','<=',$request->to);
        }

        $field = [
            'prospect.id',
            'prospect.id',
            'prospect.nama_prospect',
            'sumber_data.nama_sumber',
            'sumber_platform.nama_platform',
            'campaign.nama_campaign',
            'project.nama_project',
            'agent.nama_agent',
            'status.status',
            'prospect.created_at',
            'history_prospect.accept_at'
            ];

        $query = $query->orderBy($field[$request->order[0]['column']],$request->order[0]['dir']);

        $data = [
            'draw' => $request->draw,
            // nampilin count data total
            'recordsTotal' => HistoryProspect::leads()->count(),
            // nampilin count data terfilter
            'recordsFiltered' => $query->count(),
            // nampilin semua data
            'data' => $query->skip($request->start)->take($request->length)->get()
        ];
        // $data = HistoryProspect::leads()->get();
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = new \stdClass();
        $data->countries = DB::table('countries')->get();
        $data->sales = Sales::where(['agent_id' => Auth::user()->agent->id, 'active' => 1])->get();
        $data->source = DB::table('sumber_data')->get();
        $data->platform = DB::table('sumber_platform')->get();


        return view('SM.prospect.create', compact('data'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $prospect = Prospect::join('history_prospect as hp','hp.prospect_id','prospect.id')->where(['prospect.hp' => $request->hp, 'hp.project_id'=>$request->project_id])->select('*')->get();

        if(count($prospect) > 0){
            return redirect()->back()->with('alert_hp',true)->withInput();
        }

        $NextAgent = Agent::where('id', Auth::user()->agent->id)->get();

        if($request->sales_id){
            $NextSales = Sales::with('user')->where('id',$request->sales_id)->get();
        }

        // dd($NextAgent[0], $NextSales[0]);

        $msg = '';
        $project = Project::find($request->project_id);

        if($project->send_by == 'agent')

            $msg = Helper::blastToAgent($request->all(),$NextAgent);

        else

            $msg = Helper::blastToSales($request->all(), $NextAgent, $NextSales);

        return redirect()->route('sm.prospect.index')->with('alert',true);
    }
}
