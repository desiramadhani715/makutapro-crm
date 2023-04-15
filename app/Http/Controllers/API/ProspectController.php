<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\HistoryProspect;
use App\Models\HistoryInputSales;
use App\Models\HistorySales;
use App\Models\HistoryChangeStatus;
use App\Models\Prospect;
use App\Models\Sales;
use App\Models\Project;
use App\Models\Fu;
use App\Models\MediaFU;
use App\Models\LeadsClosing;
use App\Models\Status;
use App\Models\RemindStatus;
use Carbon\Carbon;


class ProspectController extends Controller
{
    public function all(Request $request){

        $leads = Prospect::join('history_prospect as hp','hp.prospect_id','prospect.id')
                        ->join('sumber_platform as sp','sp.id','prospect.sumber_platform_id')
                        ->select('prospect.id','prospect.nama_prospect','prospect.hp','prospect.email','prospect.is_pin','prospect.date_pin','prospect.created_at','prospect.status_id','sp.nama_platform','prospect.catatan_admin','fu.created_at as fudate')
                        ->leftJoin('fu','fu.id',DB::raw('(select max(`id`) as fuid from fu where fu.prospect_id = prospect.id)'))
                        ->whereRaw('(fu.created_at >= DATE_ADD(NOW(), INTERVAL -30 DAY))')
                        ->where('hp.project_id', $request->project_id)
                        ->orderBy('prospect.date_pin','desc')
                        ->orderBy('prospect.id','desc');


        if($request->id){
            $leads->join('project','project.id','hp.project_id')
                    ->join('status','status.id','prospect.status_id')
                    ->where('prospect.id',$request->id)
                    ->addSelect('project.nama_project','prospect.catatan_admin','status.status','prospect.message','prospect.hp');
        }

        if($request->nama_prospect){
            $leads->where('prospect.nama_prospect','like','%'.$request->nama_prospect.'%');
        }

        if($request->hp){
            $leads->where('prospect.hp','like',"%$request->hp%");
        }

        if ($request->status_id) {
            $leads->where('prospect.status_id',$request->status_id);
        }

        $leads = $leads->get();
        for ($i=0; $i < count($leads); $i++) { 
            $leads[$i]->project = Project::find($request->project_id);
            $leads[$i]->status = Status::find($leads[$i]->status_id);
        }
        
        return ResponseFormatter::success($leads);
        
    }

    public function store(Request $request){
        
        $prospect = Prospect::join('history_prospect as hpr','hpr.prospect_id','prospect.id')
                            ->where('prospect.hp', $request->hp)
                            ->where('hpr.project_id',$request->project_id)
                            ->get();
                            
        if(count($prospect) > 0) {
            return ResponseFormatter::error(['data' => "Nomor Handphone Sudah terdaftar"], 'Nomor Handphone Sudah terdaftar', 200);
        }

        $sales = Sales::join('users','users.id','sales.user_id')
                        ->where('users.id',Auth::user()->id)
                        ->where('sales.project_id',$request->project_id)
                        ->select('sales.*')
                        ->get();

        $project = Project::find($request->project_id);

        $sumber_data_id = 10;
        if($request->sumber_data_id != 0 || $request->sumber_data_id != null){
            $sumber_data_id = $request->sumber_data_id;
        }

        $Hp0=str_replace("+62", "0", $request->hp);
        $Hp1=str_replace("62", "0", $Hp0);
        $Hp2=str_replace(" ", "", $Hp1);
        $Hp3=str_replace("-", "", $Hp2);

        Prospect::create([
            'nama_prospect' => $request->nama_prospect,
            'email' => $request->email,
            'hp' => $Hp3,
            'gender_id' => $request->gender_id,
            'usia_id' => $request->usia_id,
            'message' => $request->message,
            'domisili_id' => $request->domisili_id,
            'tempat_kerja_id' => $request->tempat_kerja_id,
            'pekerjaan_id' => $request->pekerjaan_id,
            'penghasilan_id' => $request->penghasilan_id,
            'tertarik_tipe_unit_id' => $request->unit_id,
            'sumber_data_id' => $sumber_data_id,
            'role_by' => 6,
            'input_by' => Auth::user()->username,
            'status_id' => 3,
            'sumber_platform_id' => 8,
            'accept_status' => 1,
            'accept_at' => date(now())
        ]);


        HistoryInputSales::create([
            'prospect_id' => Prospect::max('id'),
            'pt_id' => $project->pt_id,
            'project_id' => $request->project_id,
            'agent_id' => $sales[0]->agent_id,
            'sales_id' => $sales[0]->id
        ]);

        HistoryProspect::create([
            'pt_id' => $project->pt_id,
            'project_id' => $request->project_id,
            'prospect_id' => Prospect::max('id'),
            'agent_id' => $sales[0]->agent_id,
            'sales_id' => $sales[0]->id,
        ]);

        Fu::create([
            'prospect_id' => Prospect::max('id'),
            'agent_id' => $sales[0]->agent_id,
            'sales_id' => $sales[0]->id,
            'media_fu_id' => 4,
        ]);

        HistorySales::create([
            'project_id' => $request->project_id,
            'sales_id'=> $sales[0]->id,
            'notes_dev' => $sales[0]->nama_sales.' baru saja menginput Prospect baru.',
            'subject_dev' => 'New Prospect : '.$request->NamaProspect,
            'history_by ' => 'Sales'
        ]);
        
        
        return ResponseFormatter::success(['data' => "Prospect berhasil diinput", 'message' => 'Prospect berhasil diinput']);
    
    }

    public function update(Request $request){

        $sales = Sales::join('users','users.id','sales.user_id')
                        ->where('users.id',Auth::user()->id)
                        ->where('sales.project_id',$request->project_id)
                        ->select('sales.*')
                        ->get();
                        
        $prospect = Prospect::find($request->prospect_id);

        $date_pin = null;
        if ($request->is_pin == 1) {
           $date_pin = Carbon::now();
        }

        Prospect::where(['id'=> $request->prospect_id])->update([
            'gender_id' => $request->gender_id,
            'sumber_data_id' => $request->sumber_data_id,
            'usia_id' => $request->usia_id,
            'domisili_id' => $request->domisili_id,
            'pekerjaan_id' => $request->pekerjaan_id,
            'tempat_kerja_id' => $request->tempat_kerja_id,
            'penghasilan_id' => $request->penghasilan_id,
            'tertarik_tipe_unit_id' => $request->unit_id,
            'catatan_sales' => $request->catatan_sales,
            'is_pin' => $request->is_pin,
            'date_pin' => $date_pin
        ]);

        
        HistorySales::create([
            'project_id' => $request->project_id,
            'sales_id'=> $sales[0]->id,
            'notes_dev' => $sales[0]->nama_sales.' baru saja mengubah Data Prospect.',
            'subject_dev' => 'Prospect : '.$request->NamaProspect,
            'history_by ' => 'Sales'
        ]);

        $data = [
            'data' => $request->prospect_id
        ];


        return ResponseFormatter::success($data, 'Data Prospect');
    }

    public function changeStatus(Request $request){

        $sales = Sales::join('users','users.id','sales.user_id')
                        ->where('users.id',Auth::user()->id)
                        ->where('sales.project_id',$request->project_id)
                        ->select('sales.*')
                        ->get();

        $ProspectID = $request->prospect_id;

        if($request->status_id == 5){
            
            LeadsClosing::where(['prospect_id'=>$ProspectID])->update([
                'prospect_id' => $ProspectID,
                'agent_id' => $sales[0]->agent_id,
                'sales_id' => $sales[0]->sales_id,
                'unit_id' => $request->unit_id,
                'ket_unit' =>$request->ket_unit,
                'closing_amount' => $request->harga_jual
            ]);

        }

        $imgName = null; 

        if ($request->file('ChatEvidenceFile')) {
            $imgName = time().'.'.$request->file('ChatEvidenceFile')->extension();
            $request->ChatEvidenceFile->storeAs('public/ChatEvidenceFile', $imgName);
        }
        
        $NoteStandard = str_replace('"','',$request->Note);

        Prospect::where(['id' => $ProspectID])->update([
            'edit_by' => Auth::user()->username,
            'status_id' => $request->status_id,
            'status_date' => date(now())
        ]);

        HistoryChangeStatus::create([
            'user_id' => Auth::user()->id,
            'prospect_id' => $ProspectID,
            'status_id' => $request->status_id,
            'standard_id' => $request->standard_id,
            'note_standard' => $NoteStandard,
            'chat_file' => $imgName,
            'role_id' => 6
        ]);

        return ResponseFormatter::success($ProspectID,'Data berhasil di update'); 

    }

    public function FollowUpLeads(Request $request){

        $ProspectID = $request->input('prospect_id');
        $prospect = Prospect::find($ProspectID);
        $sales = Sales::where('user_id',Auth::user()->id)->get();
        $project = Project::find($sales[0]->project_id);
        $mediaFU = MediaFu::find($request->media_fu_id);

        if($prospect->status_id == 1 || $prospect->status_id == 7){

            $prospect->status_id = 2;
            $prospect->status_date = date('Y-m-d H:i:s');
            $prospect->accept_at = date('Y-m-d H:i:s');
            $prospect->save();

            RemindStatus::create([
                'sales_id' => $sales[0]->id,
                'prospect_id' => $ProspectID
            ]);

            HistoryChangeStatus::create([
                'user_id' => $sales[0]->user_id,
                'prospect_id' => $ProspectID,
                'status_id' => 2,
                'standard_id' => 23,
                'role_id' => 6
            ]);
        }

        Fu::create([
            'prospect_id' => $ProspectID,
            'agent_id' => $sales[0]->agent_id,
            'sales_id' => $sales[0]->id,
            'media_fu_id' => $mediaFU->id,
        ]);

        HistorySales::create([
            'project_id' => $sales[0]->project_id,
            'sales_id'=> $sales[0]->id,
            'notes_dev' => $sales[0]->nama_sales.' baru saja Follow Up Leads melalui '.$mediaFU->nama_media.'.',
            'subject_dev' => 'Leads : '.$sales[0]->KodeProject.' - '.$prospect->nama_prospect,
            'history_by' => 'Sales'
        ]);

        return ResponseFormatter::success($ProspectID, 'Data Prospect');
    }

}
