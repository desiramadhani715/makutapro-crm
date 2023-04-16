<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Sales;
use App\Models\Prospect;
use App\Models\Fu;
use App\Models\HistoryChangeStatus;

use \stdClass;

class UserController extends Controller
{
    public function user(){
        $user = Auth::user();
        try {
            if($user){
                return ResponseFormatter::success($user);
            }
            else{
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ],`User Not Found`, 200);
            }

        } catch (\Throwable $th) {

            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $exception,
            ],'Authentication Failed', 500);

        }
    }

    public function performance(Request $request){
        $sales = Sales::join('users','users.id','sales.user_id')
                        ->where('users.id',Auth::user()->id)
                        ->where('sales.project_id',$request->project_id)
                        ->select('sales.*')
                        ->get();

        $leads = Prospect::join('history_prospect as hp','hp.prospect_id','prospect.id')
                            ->join('sales','sales.id','hp.sales_id')
                            ->where('sales.id', $sales[0]->id)
                            ->whereBetween('prospect.status_id',[2, 6])
                            ->selectRaw('time(prospect.created_at) as created_at, prospect.id')
                            ->orderBy('prospect.id','desc')
                            ->get();
                            
        // return Fu::where('prospect_id',$leads[0]->id)->first();

        /**                       FOLLOW UP SPEED FORMULA
        * __________________________________________________________________
        *| CATEGORY  |  PERSENTASE OF TOTAL LEADS  |  TIME FOLLOW UP LEADS  |
        * ------------------------------------------------------------------
        *| FAST      |            >85%             |        < 30 mins       |
        *| AVERRAGE  |       >60% and <85%         |        < 30 mins       |
        *| SLOW      |            <60%             |        < 30 mins       |
        * ------------------------------------------------------------------

        *  MISSING LEADS FORMULA
        * (total leads (number move > 0) / total all leads) * 0.2
        
        * SALES CONVERTION FORMULA 
        * (total leads closing / total leads) * 0.2 */

        for ($i=0; $i < $leads->count(); $i++) { 

            $Fu = Fu::where('prospect_id',$leads[$i]->id)->first(); // add where sales code (kalo udh update database)
            $addDate = strtotime($leads[$i]->created_at);

            return round(abs($addDate - strtotime($Fu->created_at)) / 60, 2);
        }
    }

    public function activity(Request $request){

        $sales = Sales::join('users','users.id','sales.user_id')
                        ->where('users.id',Auth::user()->id)
                        ->where('sales.project_id',$request->project_id)
                        ->select('sales.*')
                        ->get();

        $historyCS = HistoryChangeStatus::join('status','status.id','history_change_status.status_id')
                                        ->join('users','users.id','history_change_status.user_id')
                                        ->join('sales','sales.user_id','users.id')
                                        ->where('sales.id',$sales[0]->id)
                                        ->where('history_change_status.role_id',6)
                                        ->select(
                                            'status.id',
                                            'status.status',
                                            'users.name',
                                            'history_change_status.created_at',
                                            'history_change_status.chat_file',
                                            'history_change_status.note_standard',
                                            'history_change_status.role_id')
                                        ->orderBy('history_change_status.created_at','desc')
                                        ->get();

        $historyFU = Fu::join('sales','sales.id','fu.sales_id')
                        ->join('prospect','fu.prospect_id','prospect.id')
                        ->join('media_fu as mf','mf.id','fu.media_fu_id')
                        ->where('sales.id',$sales[0]->id)
                        ->where('mf.id','!=',4)
                        ->select('mf.nama_media','fu.created_at','prospect.nama_prospect')
                        ->orderBy('fu.created_at','desc')
                        ->get();

        $data = collect($historyCS)->merge($historyFU);
        $data = $data->sortByDesc('created_at');

        return ResponseFormatter::success($data->values()->all());

    }

    public function archieve(Request $request){

        $leads = Prospect::join('history_prospect as hp','hp.prospect_id','prospect.id')
                        ->join('sumber_platform as sp','sp.id','prospect.sumber_platform_id')
                        ->select('prospect.id','prospect.nama_prospect','prospect.created_at','sp.nama_platform','prospect.catatan_admin','prospect.status_id','fu.created_at as fudate')
                        ->leftJoin('fu','fu.id',DB::raw('(select max(`id`) as fuid from fu where fu.prospect_id = prospect.id)'))
                        ->whereRaw('(fu.created_at <= DATE_ADD(NOW(), INTERVAL -30 DAY) OR (fu.id is null AND prospect.status_id !=1))')
                        ->where('hp.project_id', $request->project_id)
                        ->orderBy('prospect.id','desc')
                        ->get();
        
        return ResponseFormatter::success($leads);
            
    }
}
