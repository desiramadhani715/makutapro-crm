<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Unit extends Model
{
    use HasFactory;
    protected $table = 'unit';
    protected $fillable = [
        'project_id',
        'unit_name',
        'unit_class'
    ];

    public static function getUnits(){
        return DB::table('unit as u')
                ->join('project as p','p.id','u.project_id')
                ->join('pt','pt.id','p.pt_id')
                ->where('pt.user_id',Auth::user()->id)
                ->select('p.nama_project','u.unit_name','u.id')
                ->orderBy('u.id','desc')
                ->get();
    }
}
