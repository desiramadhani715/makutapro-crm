<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Models\Sales;
use App\Models\Agent;
use App\Models\User;
use App\Models\HistoryProspect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Mail\AccountAccessMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($agent_id)
    {
        // ==============>> SCRIPT 1 USER FOR MULTIPLE SALES ACCOUNT <<==============
        // $data = Sales::all();
        // // dd($data);
        // for ($i=0; $i < count($data); $i++) {
        //     $usr = User::where('hp',$data[$i]->hp)->get();
        //     // dd($usr);
        //     if(count($usr)>1){ //jika sudah ada user
        //         for ($j=0; $j < count($usr); $j++) {
        //             # code...
        //             if($usr[$j]->role == 6){ //cek apakah belum ada user dengan role sales
        //                 // dd('if');
        //                 User::where('id',$data[$i]->user_id)->update([
        //                     'hp' => $data[$i]->hp,
        //                     'email' => $data[$i]->email,
        //                 ]);
        //             }
        //         }
        //     }else{
        //         // dd('else');
        //         $temp = User::find($data[$i]->user_id);
        //         // dd($temp);
        //         if($temp->hp == null){
        //             // dd('if');
        //             User::where('id',$data[$i]->user_id)->update([
        //                 'hp' => $data[$i]->hp,
        //                 'email' => $data[$i]->email,
        //             ]);
        //         }

        //     }
        // } die;
         // ==============>> END OF SCRIPT 1 USER FOR MULTIPLE SALES ACCOUNT <<==============

        $data = Sales::join('users','users.id','sales.user_id')
                        ->where('agent_id',$agent_id)
                        ->select('sales.id','sales.kode_sales','sales.nama_sales','sales.sort','sales.active','sales.created_at','users.username','users.hp','users.email','users.photo','users.ktp','users.nick_name','users.gender','users.birthday')
                        ->get();



        for ($i=0; $i < count($data); $i++) {
            $closingAmount = Sales::join('leads_closing','leads_closing.sales_id','sales.id')
                                ->select(DB::raw('sum(leads_closing.closing_amount) as closing_amount'))
                                ->where('leads_closing.sales_id',$data[$i]->id)
                                ->get();

            $prospect = HistoryProspect::where('sales_id',$data[$i]->id)
                                        ->select(DB::raw('count(id) as total_prospect'))
                                        ->get();

            $data[$i]->closing_amount = $closingAmount[0]->closing_amount;
            $data[$i]->total_prospect = $prospect[0]->total_prospect;
            $data[$i]->agent_id  = $agent_id;
            $data[$i]->photo = $data[$i]->photo ? Config::get('app.url').'/public/storage/user/'.$data[$i]->photo : null;
        }

        return view('pages.sales.index',compact('data','agent_id'));
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
    public function store(Request $request, $agent_id)
    {
        $imageName = '';
        if ($request->photo) {
            $imageName = time().'.'.$request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->storeAs('public/user', $imageName);
        }
        // cek apakah no hp tersebut sudah ada di tbl user sebagai role sales
        $user = User::where(['hp' => $request->hp, 'role_id' => 6])->first();

        $temp = strtolower(str_replace(' ','',$request->full_name));
        $temp_username = str_replace('-','',$temp);
        $username = substr($temp_username,-strlen($temp_username),3) . substr($request->hp, strlen($request->hp)-4, 4);
        $password = substr($request->hp, strlen($request->hp)-6, 6);
        $kode_sales = substr(strtoupper($request->full_name), 0, 3) . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $sort = Sales::getNextSalesSort($agent_id);

        $agent = Agent::with('user')->with('project')->find($agent_id);

        try {
            DB::beginTransaction();
            if(!$user){
                $user = new User();
                $user->role_id = 6;
                $user->name = $request->full_name;
                $user->nick_name = $request->nick_name;
                $user->username = $username;
                $user->password = bcrypt($password);
                $user->email = $request->email;
                $user->hp = $request->hp;
                $user->photo = $imageName;
                $user->gender = $request->gender;
                $user->birthday = Carbon::createFromFormat('m/d/Y', $request->birthday)->format('Y-m-d');
                $user->save();
            }

            $sales = new Sales();
            $sales->user_id = $user->id;
            $sales->project_id = $agent->project_id;
            $sales->agent_id = $agent_id;
            $sales->kode_sales = $kode_sales;
            $sales->nama_sales = $request->full_name;
            $sales->sort = $sort+1;
            $sales->save();

            DB::commit();

            $destination = '62'.substr($agent->hp,1);
            $message = "Hallo ".ucwords($request->full_name)." Anda telah terdaftar sebagai Sales dengan nama koordinator $agent->pic untuk project ".$agent->project->nama_project.", berikut akses untuk login \n\nUsername : $username \n Password : $password \n Link Download Aplikasi: https://play.google.com/store/apps/details?id=com.crm.makutapro_app";

            // Helper::SendWA($destination, $message);

            $data = [
                'nama'=> ucwords($request->full_name),
                'pic' => $agent->pic,
                'project' => $agent->project->nama_project,
                'username' => $username,
                'pass' => $password,
                'link' => "https://play.google.com/store/apps/details?id=com.crm.makutapro_app"
            ];

            Mail::to($request->email)->send(new AccountAccessMail($data));

            return redirect()->route('sales.index',$agent_id)->with('success','Sales Account created Successfully !');

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return redirect()->route('sales.index', $agent_id)->with('alertFailed',true);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sales $sales)
    {
        // dd($request->all(), $sales);
        try {
            $sales = Sales::with('user')->find($sales->id);
            $user = User::find($sales->user_id);

            // Update the sales data
            $sales->nama_sales = $request->nama_sales;

            $user->hp = $request->hp;
            $user->email = $request->email;
            $user->nick_name = $request->nick_name;
            $user->gender = $request->gender;
            $user->birthday = $request->birthday;

            // Update the password if it's provided
            if ($request->password) {
                $user->password = bcrypt($request->password);
            }

            // Update the photo if it's provided
            if ($request->hasFile('photo')) {
                // Delete the old photo if it exists
                if ($user->photo) {
                    Storage::delete('public/user'.$user->photo);
                }

                $imgName = time().'.'.$request->file('photo')->extension();
                $request->photo->storeAs('public/user', $imgName);
                $user->photo = $imgName;
            }

            $sales->save();
            $user->save();

            return redirect()->back()->with('success', 'Sales data updated successfully.');

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Failed to update sales data.')->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sales $sales)
    {
        $agent_id = $sales->agent_id;
        $data = HistoryProspect::where('sales_id', $sales->id)->count();

        if ($data == 0) {
            $salesUser = Sales::where('user_id', $sales->user_id)->count();

            if ($salesUser == 1)
                User::where('id',$sales->user_id)->delete();

            Sales::where('id', $sales->id)->delete();
            
            $this->resortSales($agent_id);

            return redirect()->back()->with('success', 'Sales data updated successfully.');
        }
        return redirect()->back()->with('error', 'Can not delete this Sales Data.');
    }

    public function activateSales(Request $request, Sales $sales)
    {
        $sort = Sales::getCurrentSalesSort($sales->agent_id);

        $sales->sort = $sales->active ? 0 : $sort + 1;
        $sales->active = $sales->active ? 0 : 1;
        $sales->save();

        $this->resortSales($sales->agent_id);

        return redirect()
            ->back()
            ->with('status','Sales telah ' . ($sales->active ? 'diaktifkan' : 'dinonaktifkan') . '!');
    }

    public function resortSales($agent_id)
    {
        $activeSales = Sales::where(['active' => true, 'agent_id' => $agent_id])->orderBy('sort','asc')->get();

        $sort = 1;

        foreach ($activeSales as $sales) {
            $sales->update(['sort' => $sort++]);
        }
    }
}
