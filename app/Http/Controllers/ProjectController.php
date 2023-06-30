<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Agent;
use App\Models\Pt;
use App\Models\Banner;
use App\Models\HistoryProspect;
use Illuminate\Http\Request;
use App\Http\Traits\GlobalTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
{
    use GlobalTrait;

    private $pt;

    public function __construct()
    {
        $this->pt = $this->pt();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Pt::with('project')
                ->where('user_id',Auth::user()->id)
                ->get();

        if(count($data) > 0){
            for ($i=0; $i < count($data[0]->project) ; $i++) {
                $data[0]->project[$i]->new = HistoryProspect::leads()
                                    ->where('prospect.status_id',1)
                                    ->where('history_prospect.project_id','=',$data[0]->project[$i]->id)
                                    ->count();

                $data[0]->project[$i]->process = HistoryProspect::leads()
                                    ->whereBetween('prospect.status_id',[2,3,4])
                                    ->where('history_prospect.project_id','=',$data[0]->project[$i]->id)
                                    ->count();

                $data[0]->project[$i]->notinterested = HistoryProspect::leads()
                                    ->where('prospect.status_id',6)
                                    ->where('history_prospect.project_id','=',$data[0]->project[$i]->id)
                                    ->count();

                $data[0]->project[$i]->closing = HistoryProspect::leads()
                                    ->where('prospect.status_id',5)
                                    ->where('history_prospect.project_id','=',$data[0]->project[$i]->id)
                                    ->count();
            }

            $data = $data[0]->project;
        }
        // dd($data);
        return view('pages.project.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.project.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = Pt::with('project')
                ->where('user_id',Auth::user()->id)
                ->get();

        // dd($request->all());

        // =====> Generate Kode Project <===== //

        // $nama_project = explode(" ", $request->nama_project);
        // $kode_project = "";

        // foreach ($nama_project as $w) {
        //     $kode_project .= strtoupper(mb_substr($w, 0, 1));
        // }
        // $kode_project = $data[0]->kode_pt.'-'.$kode_project;
        // dd($kode_project);

        // =====> End of Generate Kode Project <===== //

        $newproject = new Project();
        $newproject->pt_id = $data[0]->id;
        $newproject->nama_project = $request->nama_project;
        $newproject->description = $request->description;
        $newproject->send_by = $request->send_by;
        $newproject->save();

        if ($request->hasFile('banner')) {
            $projectId = $newproject->id;
            collect($request->file('banner'))->each(function ($file, $sort) use ($projectId){
                $bannerName =time() . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/banner', $bannerName);
                $newBanner = new Banner();
                $newBanner->project_id = $projectId;
                $newBanner->banner = $bannerName;
                $newBanner->sort = $sort+1;
                $newBanner->save();
            });
        }

        return redirect('/project')->with('status','Success !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
     public function show(Project $project)
    {
        $agent = Agent::where('project_id',$project->id)->get();
        $status = DB::table('status')->get();
        $project = $project::with('banner')->find($project->id);

        return view('pages.project.show', compact('project','agent','status'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $project->nama_project = $request->nama_project;
        $project->save();

        if ($request->hasFile('banner')) {
            $projectId = $project->id;
            collect($request->file('banner'))->each(function ($file, $sort) use ($projectId){
                $bannerName =time() . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/banner', $bannerName);
                $newBanner = new Banner();
                $newBanner->project_id = $projectId;
                $newBanner->banner = $bannerName;
                $newBanner->sort = $sort+1;
                $newBanner->save();
            });
        }

        return redirect()->route('project.index ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }

    public function get_prospect(Request $request){
         // return($request->search);
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
        if($request->status != ""){
            $query = $query->where('prospect.status_id','=',$request->status);
        }

        $field = [
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
            'recordsTotal' => HistoryProspect::leads()->count(),
            // nampilin count data
            'recordsFiltered' => $query->count(),
            // nampilin semua data
            'data' => $query->skip($request->start)->take($request->length)->get()
        ];
        // $data = HistoryProspect::leads()->get();
        return response()->json($data);
    }
}
