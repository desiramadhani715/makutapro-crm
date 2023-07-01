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
    protected $fillable = [
        'prospect_id',
        'pt_id',
        'project_id',
        'agent_id',
        'sales_id',
        'user_id',
        'blast_agent_id',
        'blast_sales_id',
        'move_id',
        'number_move',
        'move_date',
        'assign_date'
    ];
    // public $timestamps = false;

    public function prospect(){
        return $this->hasOne(Prospect::class, 'id');
    }

    public static function leads(){
        return DB::table('history_prospect')
                    ->join('prospect','prospect.id','history_prospect.prospect_id')
                    ->join('pt','pt.id','history_prospect.pt_id')
                    ->join('users','users.id','pt.user_id')
                    ->where('users.id',Auth::user()->id);
    }

    public static function all_leads(){
        $query = DB::table('history_prospect')
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
                ->leftJoin('gender','gender.id','prospect.gender_id')
                ->leftJoin('usia','usia.id','prospect.usia_id')
                ->leftJoin('pekerjaan','pekerjaan.id','prospect.pekerjaan_id')
                ->leftJoin('penghasilan','penghasilan.id','prospect.penghasilan_id')
                ->select('prospect.*'
                ,'sumber_data.nama_sumber','sumber_platform.nama_platform','campaign.nama_campaign','project.nama_project','project.id as project_id','agent.id as agent_id','agent.kode_agent','agent.nama_agent','sales.id as sales_id','sales.nama_sales','status.status', 'gender.jenis_kelamin','usia.range_usia','pekerjaan.tipe_pekerjaan','penghasilan.range_penghasilan'
                );

        if (Auth::user()->role_id == 1) {
            $query->where('users.id',Auth::user()->id);
        }
        if (Auth::user()->role_id == 3) {
            $query->where('agent.user_id',Auth::user()->id);
        }

        return $query;

    }

    public static function get_leads_by_status($status){
        return DB::table('history_prospect as hpr')
                    ->join('project','project.id','hpr.project_id')
                    ->join('prospect','prospect.id','=','hpr.prospect_id')
                    ->join('sales','hpr.sales_id','=','sales.sales_id')
                    ->where('prospect.status_id','=',$status)
                    ->select('hpr.prospect_id','hpr.sales_id','hpr.project_id','hpr.move_date','sales.user_id','project.nama_project')
                    ->get();
    }

    public static function count_leads_by_role($src, $start_date, $end_date){

        if (Auth::user()->role_id == 1) {
            $query = HistoryProspect::leads();
        }
        if (Auth::user()->role_id == 3) {
            $query = DB::table('history_prospect as hp')
                    ->join('prospect','prospect.id','hp.prospect_id')
                    ->join('agent','agent.id','hp.agent_id')
                    ->where('agent.user_id',Auth::user()->id);
        }

        $query->select(DB::raw('DATE(prospect.created_at) as date'), DB::raw('COUNT(*) as total'))
                ->whereBetween('prospect.created_at', [$start_date, $end_date]);

        if ($src == 'Digital Source')
            $query->where('prospect.role_by','!=',6);
        if ($src == 'Sales Source')
            $query->where('prospect.role_by',6);

        return $query->groupBy('date')->get();
    }

    public static function count_leads_by_src($src, $role){
        if ($role == 'Developer') {
            $query = HistoryProspect::leads();
        }

        if ($role == 'Agent') {
            $query = DB::table('history_prospect as hp')
                    ->join('prospect','prospect.id','hp.prospect_id')
                    ->join('agent','agent.id','hp.agent_id')
                    ->where('agent.user_id',Auth::user()->id);
        }

        if ($src == 'Platform'){
            $query->join('sumber_platform','sumber_platform.id','prospect.sumber_platform_id')
                    ->select('sumber_platform.nama_platform', DB::raw('count(prospect.id) as total'))
                    ->groupBy('prospect.sumber_platform_id');
        }

        if ($src == 'Source'){
            $query->join('sumber_data','sumber_data.id','prospect.sumber_data_id')
                    ->select('sumber_data.nama_sumber', DB::raw('count(prospect.id) as total'))
                    ->groupBy('prospect.sumber_data_id');
        }

        if ($src == 'Age'){
            $query->join('usia','usia.id','prospect.usia_id')
                    ->select(DB::raw('count(prospect.id) as value'),'usia.range_usia as name')
                    ->groupBy('prospect.usia_id');
        }

        if ($src == 'Gender'){
            $query->join('gender','gender.id','prospect.gender_id')
                    ->select(DB::raw('count(prospect.id) as value'), 'gender.jenis_kelamin as name')
                    ->groupBy('prospect.gender_id');
        }

        return $query->get();
    }

}
