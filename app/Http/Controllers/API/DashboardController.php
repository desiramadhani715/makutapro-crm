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
use App\Models\Prospect;
use App\Models\Banner;
use App\Models\Status;
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

        $project = Project::find($project_id);

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

        $leads = Status::leftJoin('prospect', 'prospect.status_id', 'status.id')
                            ->leftJoin('history_prospect as hp', function ($join) use ($project_id) {
                                $join->on('hp.prospect_id', 'prospect.id')
                                    ->where('hp.project_id', $project_id)
                                    ->where('hp.user_id', Auth::user()->id);});

        $ownLeads = Prospect::with('historyProspect')
                    ->whereHas('historyProspect', function ($query) use ($project_id){
                        $query->where('project_id', $project_id)->where('user_id', Auth::user()->id);
                    })
                    ->where('sumber_platform_id',8);

        if($request->start_date && $request->end_date){
            $leads->whereBetween('prospect.created_at',[$request->start_date, $request->end_date]);
            $ownLeads->whereBetween('prospect.created_at',[$request->start_date, $request->end_date]);
        }
        else {
            $leads->whereRaw('prospect.created_at >= DATE_ADD(NOW(), INTERVAL -30 DAY) OR prospect.id IS NULL');
            $ownLeads->whereRaw('prospect.created_at >= DATE_ADD(NOW(), INTERVAL -30 DAY) OR prospect.id IS NULL');
        }

        $leadSum = $leads->select(DB::raw('count(hp.id) as total'), 'status.status')->groupBy('status.status')->get();

        $ownLeadsCount = (object) [
            'total' => $ownLeads->count(),
            'status' => 'Own Leads',
        ];

        $leadSum[] = $ownLeadsCount;

        return ResponseFormatter::success([
            'project' => $project,
            'teams' => $teams,
            'banner' => $banner,
            'leadSum' => $leadSum
        ]);
    }

    public function banner_detail($banner_id){

        $banner = Banner::where('id', $banner_id)->get()->map(function ($item) {
                    $item->banner = asset('storage/banner/'.$item->banner);
                    return $item;
                });

        if ($banner->isEmpty()) {
            return ResponseFormatter::error('Banner Not Found.');
        }

        return ResponseFormatter::success($banner);
    }
}
