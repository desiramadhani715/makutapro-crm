<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Sales;
use App\Models\Project;
use App\Models\Banner;
use App\Models\HistoryProspect;

class DashboardController extends Controller
{
    public function projects(){

        $user = Auth::user();

        $projects = Sales::join('project','project.id','sales.project_id')
                            ->where('user_id',$user->id)
                            ->select('sales.project_id','project.nama_project')
                            ->get();

        return ResponseFormatter::success(['projects' => $projects]);
                    
    }

    public function project_detail(Request $request, $project_id){

        $teams = Sales::join('users','users.id','sales.user_id')
                        ->where('project_id',$project_id)
                        ->where('users.id','!=',Auth::user()->id)
                        ->select('sales.id','sales.nama_sales','users.photo')
                        ->get();

        $banner = Banner::where('project_id', $project_id)
                        ->select('id','banner')
                        ->get()
                        ->map(function ($item) {
                            $item->banner = Config::get('app.url').'/public/storage/banner/'.$item->banner;
                            return $item;
                        });

        $leadSum = HistoryProspect::join('project','project.id','history_prospect.project_id')
                                    ->join('prospect','prospect.id','history_prospect.prospect_id')
                                    ->join('status','status.id','prospect.status_id')
                                    ->where('history_prospect.project_id', $project_id)
                                    ->select(DB::raw('count(*) as total'),'status.status')
                                    ->groupBy('status.status')
                                    ->get();

        return ResponseFormatter::success([
            'teams' => $teams,
            'banner' => $banner,
            'leadSum' => $leadSum
        ]);
    }
}
