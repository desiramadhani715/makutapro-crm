<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;
    protected $table = 'project';
    protected $fillable = ['pt_id','user_id','kode_project','nama_project','description','send_by','active','kodePt'];


    public function agent()
    {
        return $this->hasMany(Agent::class, 'project_id');
    }

    public function banner()
    {
        return $this->hasMany(Banner::class, 'project_id');
    }

    public function pt()
    {
        return $this->belongsTo(Pt::class, 'pt_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public static function get_project(){
        return DB::table('project')
                    ->join('pt','pt.id','=','project.pt_id')
                    ->where('pt.user_id','=',Auth::user()->id)
                    ->select('project.*');

    }

}
