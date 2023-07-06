<?php

namespace App\Http\Controllers\SM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;
use App\Models\Sales;
use App\Models\Agent;
use App\Models\User;
use App\Models\HistoryProspect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
    public function index()
    {
        $agent_id = Auth::user()->agent->id;

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

        return view('SM.sales.index',compact('data','agent_id'));
    }
}
