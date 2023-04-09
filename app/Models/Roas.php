<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Roas extends Model
{
    use HasFactory;
    protected $table = 'roas';

    public static function getRoas(){
        return DB::table('roas as r')
                ->join('project as p','p.id','r.project_id')
                ->join('pt','pt.id','p.pt_id')
                ->where('pt.user_id',Auth::user()->id)
                ->select('p.nama_project','r.*')
                ->orderBy('r.id','desc')
                ->get();
    }
}
