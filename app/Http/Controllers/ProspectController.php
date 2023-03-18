<?php

namespace App\Http\Controllers;

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
        $data = HistoryProspect::leads()->get();
        $project = Project::get_project()->select('project.*')->get();
        $platform = DB::table('sumber_platform')->get();
        $source = DB::table('sumber_data')->get();
        $status = DB::table('status')->get();
        
        return view('pages.prospect.index',compact('project','platform','source','status'));
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
        $data->projects = Project::get_project()->get();
        $data->source = DB::table('sumber_data')->get();
        $data->platform = DB::table('sumber_platform')->get();

        // dd($data);

        return view('pages.prospect.create', compact('data'));
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
        
        $lastBlast = HistoryBlast::with('prospect')->where('project_id',$request->project_id)->get(); //cek apakah project ini sudah pernah di blast

        $NextAgent = Agent::with('user')->where([
                        'project_id' => $request->project_id,
                        'urut_agent' => 1, 
                        'active' => 1])->get();

        $NextSales = Sales::with('user')->where(['agent_id' => $NextAgent[0]->id, 'urut_agent_sales' => 1, 'active' => 1])->get();

        // dd($NextAgent[0], $NextSales[0]);
        
        if(count($lastBlast)>0){

            $agent = Agent::with('user')->where(['project_id' => $lastBlast->max()->project_id, 'active' => 1])->get(); //get all agent dari project id
            
            $NextAgent = Agent::with('user')->where(['project_id' => $lastBlast->max()->project_id,'urut_agent' => 1, 'active' => 1])->get();
            
            $NextSales = Sales::with('user')->where(['agent_id' => $NextAgent[0]->id, 'urut_agent_sales' => 1, 'active' => 1])->get();

            if ($lastBlast->max()->blast_agent_id < $agent->max('urut_agent')) {
                $NextAgent = Agent::with('user')->where([
                    'project_id' => $lastBlast->max()->project_id,
                    'urut_agent' => $lastBlast->max()->blast_agent_id + 1,
                    'active' => 1])->get();
            }

            $sales = Sales::with('user')->where(['agent_id' => $NextAgent[0]->id, 'active' => 1])->get(); // get all sales aktif dari next agent id
            // dd($sales);
            $NextSales = Sales::with('user')->where(['agent_id' => $NextAgent[0]->id, 'urut_agent_sales' => 1, 'active' => 1])->get();

            $lastBlastAgent = HistoryBlast::with('prospect')->where('agent_id',$NextAgent[0]->id)->get();

            if(count($lastBlastAgent) > 0 && $lastBlastAgent->max()->blast_sales_id < $sales->max('urut_agent_sales')){
                
                $NextSales = Sales::with('user')->where([
                    'agent_id' => $NextAgent[0]->id, 
                    'urut_agent_sales' => $lastBlast->max()->blast_sales_id + 1, 
                    'active' => 1])->get();
            }

        }

        if($request->agent_id){
            $NextAgent = Agent::with('user')->where('id',$request->agent_id)->get();
        }


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
        
        return redirect()->route('prospect.index')->with('alert',true);
    }

    public function cek_hp(Request $request){
        $prospect = Prospect::join('history_prospect as hp','hp.prospect_id','prospect.id')->where(['prospect.hp' => $request->hp, 'hp.project_id'=>$request->project_id])->select('*')->get();
     
        if(count($prospect) > 0)
            return response()->json(['status' => false]);
        else
            return response()->json(['status' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prospect  $prospect
     * @return \Illuminate\Http\Response
     */
    public function show(Prospect $prospect)
    {
        $units = DB::table('unit')->get();
        // dd(count($units));
        // for ($i=0; $i < count($units); $i++) { 
        //     $proj = Project::where('kode_project',$units[$i]->project_id)->get();
        //     if(count($proj)>0)
        //         DB::table('unit')->where('id',$units[$i]->id)->update([
        //             'project_id' => $proj[0]->id
        //         ]);
        // }die;
        $data = HistoryProspect::all_leads()->where('prospect.id',$prospect->id)->get()[0];
        $history_status = HistoryChangeStatus::find(HistoryChangeStatus::where('prospect_id',$prospect->id)->max('id'));

        $data->source = DB::table('sumber_data')->get();
        $data->platform = DB::table('sumber_platform')->get();
        $data->gender = DB::table('gender')->get();
        $data->usia = DB::table('usia')->get();
        $data->pekerjaan = DB::table('pekerjaan')->get();
        $data->countries = DB::table('countries')->get();
        $data->penghasilan = DB::table('penghasilan')->get();
        $data->provinces = Province::all();
        $data->cities = City::all();
        $data->domisili_prov = null;
        $data->domisili_city = null;
        $data->work_prov = null;
        $data->work_city = null;
        $data->statuslist = DB::table('status')->whereNotIn('id',[$data->status_id,1,7])->get();
        $data->last_updated_status = null;
        $data->standard_id = null;
        $data->reason = null;
        $data->reasons = Standard::where('status_id',$data->status_id)->get();
        $data->note_standard = null;
        $data->note_standard = null;
        $data->units = DB::table('unit')->where('project_id',$data->project_id)->get();;
        $data->unit_id = null;
        $data->unit_name = null;
        $data->ket_unit = null;
        $data->closing_amount = null;


        if($data->sumber_data_id){
            $data->source = $data->source->whereNotIn('id',[$data->sumber_data_id]);
        }
        if($data->sumber_platform_id){
            $data->platform = $data->platform->whereNotIn('id',[$data->sumber_platform_id]);
        }
        if($data->gender_id){
            $data->gender = $data->gender->whereNotIn('id',[$data->gender_id]);
        }
        if($data->usia_id){
            $data->usia = $data->usia->whereNotIn('id',[$data->usia_id]);
        }
        if($data->pekerjaan_id){
            $data->pekerjaan = $data->pekerjaan->whereNotIn('id',[$data->pekerjaan_id]);
        }
        if($data->kode_negara){
            $country =  DB::table('countries')->where('calling_code',$data->kode_negara)->get()[0];
            $data->country = $country->country;
        }
        if($data->penghasilan_id){
            $data->penghasilan = $data->penghasilan->whereNotIn('id',[$data->penghasilan_id]);
        }
        if($data->domisili_id){
            $city = City::find($data->domisili_id);
            $prov = Province::find($city->province_id);
            $data->domisili_city = $city->city;
            $data->domisili_prov = $prov->province;
        }
        if($data->tempat_kerja_id){
            $city = City::find($data->tempat_kerja_id);
            $prov = Province::find($city->province_id);
            $data->work_city = $city->city;
            $data->work_prov = $prov->province;
        }
        if ($history_status) {
            $st = Standard::find($history_status->standard_id);
            if($st){
                $data->reason = $st->alasan;
                $data->standard_id = $st->id;   
                $data->note_standard = $history_status->note_standard;   
            }
            // else{
            //     $data->reasons =  Standard::where('status_id',$data->status_id);
            // }
            $data->last_updated_status = User::find($history_status->user_id)->name; 
        }
        if ($data->status_id == 5) {
            $closing_data = ClosingLeads::where('prospect_id',$prospect->id)->get()[0];
            $data->units = DB::table('unit')->where('project_id',$data->project_id)->whereNotIn('id',[$closing_data->unit_id])->get();
            $data->unit_id = $closing_data->unit_id;
            $data->ket_unit = $closing_data->ket_unit;
            $data->closing_amount = $closing_data->closing_amount;
            $data->unit_name = DB::table('unit')->find($closing_data->unit_id)->unit_name;
        }
        // dd($data);
        return view('pages.prospect.detail',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prospect  $prospect
     * @return \Illuminate\Http\Response
     */
    public function edit(Prospect $prospect)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prospect  $prospect
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prospect $prospect)
    {
        $history = HistoryProspect::all_leads()->where('prospect.id',$prospect->id)->get()[0];
        // dd($history, $prospect);

        $prospect->sumber_platform_id = $request->sumber_platform_id;
        $prospect->sumber_data_id = $request->sumber_data_id;
        $prospect->full_path_ref = $request->full_path_ref;
        $prospect->utm_source = $request->utm_source;
        $prospect->utm_medium = $request->utm_medium;
        $prospect->utm_campaign = $request->utm_campaign;

        $prospect->nama_prospect = $request->nama_prospect;
        $prospect->kode_negara = $request->kode_negara;
        $prospect->hp = $request->hp;
        $prospect->email = $request->email;
        $prospect->gender_id = $request->gender_id;
        $prospect->usia_id = $request->usia_id;
        $prospect->pekerjaan_id = $request->pekerjaan_id;
        $prospect->penghasilan_id = $request->penghasilan_id;
        $prospect->domisili_id = $request->domisili_id;
        $prospect->tempat_kerja_id = $request->tempat_kerja_id;
        $prospect->message = $request->message;
        $prospect->catatan_admin = $request->catatan_admin;

        if($request->status_id != $prospect->status_id){
            HistoryChangeStatus::create([
                'user_id' => Auth::user()->id,
                'prospect_id' => $prospect->id,
                'status_id' => $request->status_id,
                'standard_id' => $request->standard_id,
                'note_standard' => $request->note_standard,
                'role_id' => 1
            ]);

            $prospect->status_id = $request->status_id;
            $prospect->status_date = date(now());

            if($request->status_id == 5){
                ClosingLeads::create([
                    'prospect_id' =>$prospect->id,
                    'agent_id' => $history->agent_id,
                    'sales_id' => $history->sales_id,
                    'unit_id' => $request->unit_id,
                    'ket_unit' => $request->ket_unit,   
                    'closing_amount' => str_replace('.','',str_replace('Rp. ','',$request->closing_amount))
                ]);
            }
        }

        HistorySales::create([
            'sales_id'=> $history->sales_id,
            'notes' => 'Data Prospect an. '.$prospect->nama_prospect.' telah di ubah oleh Developer',
            'subject' => 'Prospect : '.$history->nama_project,
            'project_id' => $history->project_id,
            'history_by' => 'Developer'
        ]);


        $prospect->save();
        return redirect()->route('prospect.index')->with('alert',true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prospect  $prospect
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prospect $prospect)
    {
        $hb = HistoryBlast::where('prospect_id', $prospect->id)->delete();
        $hp = HistoryProspect::where('prospect_id', $prospect->id)->delete();
        $p  = HistoryChangeStatus::where('prospect_id', $prospect->id)->delete();
        $his = HistoryInputSales::where('prospect_id', $prospect->id)->delete();
        $hi = HistoryProspectMove::where('prospect_id',$prospect->id)->delete();

        Prospect::destroy($prospect->id);
        return redirect()->back()->with('alert','Prospect berhasil di hapus !');
    }

   
}