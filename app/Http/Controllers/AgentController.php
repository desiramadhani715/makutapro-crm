<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Models\Agent;
use App\Models\ProjectAgent;
use App\Models\Project;
use App\Models\Sales;
use App\Models\User;
use App\Models\Fu;
use App\Models\HistoryChangeStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helper\Helper;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $fu = Fu::all();
        // for ($i=0; $i < count($fu); $i++) { 
        //     $agent = Agent::where('kode_agent',$fu[$i]->KodeAgent)->get();
        //     $sales = Sales::where('kode_sales',$fu[$i]->KodeSales)->get();
        //     $data = Fu::find($fu[$i]->id);
        //     Fu::where('id',$fu[$i]->id)->update([
        //         'agent_id' => $agent[0]->id,
        //         'sales_id' => $sales[0]->id,
        //     ]);
        //     // $data->agent_id = $agent->id;
        //     // $data->sales_id = $sales->id;
        //     // $data->save();
        // }die;
        // $agent = Agent::all();
        
        // for ($i=0; $i < count($agent); $i++) { 
        //     User::where('id',$agent[$i]->user_id)->update([
        //         'hp' => $agent[$i]->hp,
        //         'email' => $agent[$i]->email,
        //     ]);
        // }die;


        $data = Agent::agent()->get();
        $project = Project::get_project()->get();

        foreach ($data as $key) {
            $closingAmount = Agent::agent()   
                                ->join('leads_closing','leads_closing.agent_id','agent.id')
                                ->select(DB::raw('sum(leads_closing.closing_amount) as closing_amount'))
                                ->where('leads_closing.agent_id',$key->id)
                                ->get();

            $key->closing_amount = $closingAmount[0]->closing_amount;
            $key->photo = $key->photo ? Config::get('app.url').'/public/storage/user/'.$key->photo : null;
        }

        return view('pages.agent.index', compact('data','project'));
    }

    public function active(Request $request){
        // ambil data agent yang ingin di aktifkan
        $agent = Agent::find($request->agent_id);
        
        $UrutAgentMax = Agent::where(['project_id' => $agent->project_id])->max('urut_agent');

        $agent->urut_agent = $UrutAgentMax+1;
        $agent->active = 1;
        $agent->save();

        ProjectAgent::where(['agent_id' => $agent->id])->update([
            'urut_project_agent' => $UrutAgentMax+1
        ]);

        User::where(['id' => $agent->user_id])->update([
            'active' => 1,
        ]);

        return redirect()
            ->back()
            ->with('status', 'Agent telah di aktifkan!');
    }

    public function nonactive(Request $request){
        // ambil data agent yang ingin di non aktifkan
        $agent = Agent::find($request->agent_id);
        
        // ambil data agent yang no urutnya lebih besar dari agent yg ingin di non aktifkan
        $data = Agent::where('project_id', $agent->project_id)
                        ->where('urut_agent','>',$agent->urut_agent)
                        ->get();


        // update data agent (urut agent) yang no urut nya lebih besar dari agent yg ingin di non aktifkan
        if (count($data) > 0) {
            if($data[0]->urut_agent != $agent->urut_agent + 1){
                return redirect()
                        ->back()
                        ->with('status', 'Ada kesalahan saat menonaktifkan agent');
            }
            for ($i = 0; $i < count($data); $i++) {
                Agent::where(['id' => $data[$i]->id])->update([
                    'urut_agent' => $data[$i]->urut_agent - 1,
                ]);
            }
        }
        
        $agent->urut_agent = 0;
        $agent->active = 0;
        $agent->save();

        ProjectAgent::where(['agent_id' => $agent->id])->update([
            'urut_project_agent' => 0
        ]);

        User::where(['id' => $agent->user_id])->update([
            'active' => 0,
        ]);

        
        return redirect()
            ->back()
            ->with('status', 'Agent telah di non-aktifkan!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd(ucwords($request->nama_agent));
        $imageName = '';
        if ($request->photo) {
            $imageName = $request->file('photo')->getClientOriginalName();
            $request->file('photo')->storeAs('public/user', $imageName);
        }

        $kodeAgent = substr(strtoupper($request->nama_agent), 0, 3) . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $username = substr($request->nama_agent, 0, 3) . str_pad(mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        $pass = substr($request->hp, strlen($request->hp)-6, 6);
        $sort = Agent::getNextAgentSort($request->project_id);

        $project = Project::find($request->project_id);
        
        try {

            DB::beginTransaction();

            $user = new User();
            $user->role_id = 3;
            $user->name = $request->nama_agent;   
            $user->username = $username;   
            $user->password = bcrypt($pass);
            $user->email = $request->email;
            $user->hp = $request->hp;
            $user->photo = $imageName;
            $user->save();
            
            $agent = new Agent();
            $agent->user_id = $user->id;
            $agent->project_id = $request->project_id;
            $agent->kode_agent = $kodeAgent;
            $agent->nama_agent = $request->nama_agent;
            $agent->urut_agent = $sort + 1;
            $agent->save();

            $projectAgent = new ProjectAgent();
            $projectAgent->project_id = $request->project_id;
            $projectAgent->agent_id = $agent->id;
            $projectAgent->urut_project_agent = $sort + 1;
            $projectAgent->save();


            // Commit the transaction if everything was successful
            DB::commit();

            $destination = '62'.substr($request->hp,1);
            $message = "Hallo " .ucwords($request->nama_agent). " Anda telah terdaftar sebagai salah satu Koordinator sales di project $project->nama_project, berikut akses untuk login \n\nUsername : $username \nPassword : $pass \nLink : https://agent.makutapro.id/login.php";

            Helper::SendWA($destination, $message);

        } catch (\Throwable $th) {
            // If an error occurred, rollback the transaction
            DB::rollback();
            return redirect()->route('agent.index')->with('alertFailed',true);
        }

        return redirect()->route('agent.index')->with('alertSuccess',true);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function show(Agent $agent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function edit(Agent $agent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agent $agent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agent $agent)
    {
        $sales = Sales::where('agent_id', $agent->id)->get();
        
        if(count($sales) == 0) { 
            try {
                DB::beginTransaction();

                $projectAgent = ProjectAgent::where('agent_id', $agent->id)->delete();
                $agent = Agent::find($agent->id);
                $user = User::find($agent->user_id);
                $agent->delete();
                $user->delete();

                DB::commit();

            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->route('agent.index')->with('alertFailed',true);
            }
            return redirect()->route('agent.index')->with('alertDeleted',true);
        }
        return redirect()->route('agent.index')->with('alertFailed',true);
    }

    public function get_agent(Request $request)
    {
        $agent = Agent::where(['project_id' => $request->project,'active' => 1])->pluck(
            'nama_agent',
            'id'
        );

        return response()->json($agent);
    }

    public function getsales(Request $request)
    {
        $sales = Sales::where(['sales.agent_id' => $request->agent, 'active' => 1])
                        ->pluck('nama_sales','id');

        return response()->json($sales);
    }
}