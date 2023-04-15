<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Prospect extends Model
{
    use HasFactory;
    protected $table = 'prospect';
    protected $fillable = [
        'nama_prospect',
        'kode_negara',
        'hp',
        'email',
        'message',
        'catatan',
        'catatan_admin',
        'catatan_sales',
        'verified_status',
        'verified_at',
        'accept_status',
        'accept_at',
        'gender_id',
        'status_id',
        'status_date',
        'usia_id',
        'domisili_id',
        'tempat_kerja_id',
        'pekerjaan_id',
        'penghasilan_id',
        'sumber_data_id',
        'note_sumber_data',
        'sumber_platform_id',
        'sumber_ads_id',
        'campaign_id',
        'not_interested_id',
        'not_interested_at',
        'role_by',
        'input_by',
        'edit_by',
        'tertarik_tipe_unit_id',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'full_path_ref',
        'is_pin',
        'date_pin'
    ];

    public function historyProspect(){
        return $this->hasOne(HistoryProspect::class, 'prospect_id');
    }

    public function blast(){
        return $this->hasMany(HistoryBlast::class, 'prospect_id');
    }

    public static function archieve(){
        // $data =  DB::select("select * from (
        //     select p.*, (select max(id) from fu f where f.prospect_id = p.id) as pid from prospect p
        // ) p 
        // left join fu f on f.id = p.pid");

        $leads = Prospect::join('history_prospect as hp','hp.prospect_id','prospect.id')
                            ->join('sumber_platform as sp','sp.id','prospect.sumber_platform_id')
                            ->where('hp.project_id', 22)
                            ->select('prospect.id','prospect.nama_prospect','prospect.created_at','sp.nama_platform','prospect.catatan_admin','prospect.status_id','fu.created_at as fudate')
                            ->leftJoin('fu','fu.id',DB::raw('(select max(`id`) as fuid from fu where fu.prospect_id = prospect.id)'))
                            ->whereRaw('fu.created_at <= DATE_ADD(NOW(), INTERVAL -30 DAY) OR (fu.id is null AND prospect.status_id !=1)')
                            ->orderBy('prospect.id','desc')
                            ->get();

        return $leads;
    }

}
