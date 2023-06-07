<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Agent extends Model
{
    use HasFactory;
    protected $table = 'agent';
    protected $fillable = ['user_id','project_id','kode_agent','nama_agent','urut_agent','pic'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public static function agent(){
        return DB::table('agent')
                    ->join('project','project.id','agent.project_id')
                    ->join('pt','pt.id','project.pt_id')
                    ->join('users','users.id','agent.user_id')
                    ->where('pt.user_id',Auth::user()->id)
                    ->select('agent.*','project.nama_project','users.username','users.hp','users.email','users.created_at','users.active','users.photo');

    }

    public static function getNextAgentSort($projectId)
    {
        $currentSort = 0;

        // Mengambil nilai sort dari agent saat ini
        $currentAgent = Agent::where('project_id', $projectId)->orderBy('id', 'desc')->first();
        if ($currentAgent) {
            $currentSort = $currentAgent->urut_agent;
        }

        // Mendapatkan nilai sort untuk agent selanjutnya
        $nextAgent = Agent::where('urut_agent', '>', $currentSort)->where('project_id', $projectId)->orderBy('urut_agent', 'asc')->first();
        $nextSort = $currentSort;

        if ($nextAgent) {
            $nextSort = $nextAgent->urut_agent;
        }

        return $nextSort;
    }
}
