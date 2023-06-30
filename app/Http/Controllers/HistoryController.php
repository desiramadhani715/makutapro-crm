<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fu;
use App\Models\Agent;
use App\Models\Sales;
use App\Models\HistoryChangeStatus;
use App\Models\HistoryProspectMove;

class HistoryController extends Controller
{
    public function historyCs(Request $request){
        $history = HistoryChangeStatus::join('status','status.id','history_change_status.status_id')
                    ->join('users','users.id','history_change_status.user_id')
                    ->join('standard','standard.id','history_change_status.standard_id')
                    ->where('history_change_status.prospect_id',$request->prospect_id)
                    ->select(
                        'status.id',
                        'status.status',
                        'users.name',
                        'standard.alasan',
                        'history_change_status.created_at',
                        'history_change_status.chat_file',
                        'history_change_status.standard_id',
                        'history_change_status.role_id'
                    )
                    ->orderBy('history_change_status.created_at','desc')
                    ->get();

        return response()->json($history);
    }

    public function historyMp(Request $request){

        $history = HistoryProspectMove::join('sales','sales.id','history_prospect_move.prev_sales_id')
                                        ->where('history_prospect_move.prospect_id',$request->prospect_id)
                                        ->select('sales.nama_sales','history_prospect_move.created_at')
                                        ->orderBy('history_prospect_move.created_at','desc')
                                        ->get();


        return response()->json($history);
    }

    public function historyFu(Request $request){
        $history = Fu::join('sales','sales.id','fu.sales_id')
                        ->join('media_fu as mf','mf.id','fu.media_fu_id')
                        ->where('fu.prospect_id',$request->prospect_id)
                        ->select('sales.nama_sales','mf.nama_media','fu.created_at')
                        ->orderBy('fu.created_at','desc')
                        ->get();

        return response()->json($history);
    }


}
