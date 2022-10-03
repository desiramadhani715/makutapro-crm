<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryProspect extends Model
{
    use HasFactory;
    protected $table = 'history_prospect';
    protected $fillable = ['prospect_id','pt_id','project_id','agent_id','sales_id','blast_agent_id','blast_sales_id','move_id','number_move','move_date','assign_date'];
    // public $timestamps = false;

    public static function total_leads(){
        return DB::table('history_prospect')
                    ->join('prospect','prospect.id','history_prospect.prospect_id')
                    ->join('pt','pt.id','history_prospect.pt_id')
                    ->join('users','users.id','pt.user_id')
                    ->where('users.id',Auth::user()->id);
    }

    public static function all_leads(){
        return DB::table('history_prospect')
                ->leftJoin('prospect','prospect.id','history_prospect.prospect_id')
                ->leftJoin('pt','pt.id','history_prospect.pt_id')
                ->leftJoin('project','project.id','history_prospect.project_id')
                ->leftJoin('agent','agent.id','history_prospect.agent_id')
                ->leftJoin('sales','sales.id','history_prospect.sales_id')
                ->leftJoin('users','users.id','pt.user_id')
                ->leftJoin('status','status.id','prospect.status_id')
                ->leftJoin('sumber_data','sumber_data.id','prospect.sumber_data_id')
                ->leftJoin('sumber_platform','sumber_platform.id','prospect.sumber_platform_id')
                ->leftJoin('campaign','campaign.id','prospect.campaign_id')
                ->leftJoin('leads_not_interested','leads_not_interested.prospect_id','prospect.id')
                ->leftJoin('not_interested','not_interested.id','leads_not_interested.not_interested_id')
                ->select('prospect.*','sumber_data.nama_sumber','sumber_platform.nama_platform','campaign.nama_campaign','project.nama_project','agent.kode_agent','sales.nama_sales','status.status','not_interested.alasan')
                ->where('users.id',Auth::user()->id)
                ->orderBy('prospect.id','desc');
                
    }

}