<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Campaign extends Model
{
    use HasFactory;

    protected $table = 'campaign';
    protected $fillable = [
        'project_id',
        'nama_campaign'
    ];

    public static function getCampaign(){
        return DB::table('campaign as c')
                ->join('project as p','p.id','c.project_id')
                ->join('pt','pt.id','p.pt_id')
                ->where('pt.user_id',Auth::user()->id)
                ->select('p.nama_project','c.nama_campaign','c.id')
                ->orderBy('c.id','desc')
                ->get();
    }
}
