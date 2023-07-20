<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\HistoryProspect;
use App\Models\HistoryProspectMove;
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
use App\Models\Standard;
use App\Models\RemindStatus;
use App\Models\Age;
use App\Models\Domicile;
use App\Models\Income;
use App\Models\Occupation;
use App\Models\WorkLocation;
use App\Models\Province;
use Carbon\Carbon;


class ProspectController extends Controller
{
    public function all(Request $request){

        $oldLeads = Prospect::join('history_prospect as hp', 'hp.prospect_id', 'prospect.id')
            ->join('sumber_platform as sp', 'sp.id', 'prospect.sumber_platform_id')
            ->select('prospect.id', 'prospect.nama_prospect', 'prospect.hp', 'prospect.email', 'prospect.is_pin', 'prospect.date_pin', 'prospect.created_at', 'prospect.status_id', 'sp.nama_platform', 'prospect.catatan_admin')
            ->leftJoin('fu', 'fu.id', DB::raw('(select max(`id`) as fuid from fu where fu.prospect_id = prospect.id)'))
            ->whereRaw('fu.created_at >= DATE_ADD(NOW(), INTERVAL -30 DAY)')
            ->where('hp.project_id', $request->project_id)
            ->where('hp.user_id', Auth::user()->id)
            ->with('notesAdmin');

        $newLeads = Prospect::join('history_prospect as hp', 'hp.prospect_id', 'prospect.id')
            ->join('sumber_platform as sp', 'sp.id', 'prospect.sumber_platform_id')
            ->select('prospect.id', 'prospect.nama_prospect', 'prospect.hp', 'prospect.email', 'prospect.is_pin', 'prospect.date_pin', 'prospect.created_at', 'prospect.status_id', 'sp.nama_platform', 'prospect.catatan_admin')
            ->where('hp.project_id', $request->project_id)
            ->where('hp.user_id', Auth::user()->id)
            ->where('prospect.verified_status',1)
            ->whereIn('prospect.status_id', [1, 7]);

        $leads = $oldLeads->union($newLeads);

        $leads->orderBy('date_pin', 'desc')
                ->orderBy('id', 'desc');

        $leads = $leads->get();

        foreach ($leads as $lead) {
            $lead->project = Project::find($request->project_id);
            $lead->status = Status::find($lead->status_id);
        }

        return ResponseFormatter::success($leads);

    }

    public function get(Request $request){

        $leads = Prospect::join('history_prospect as hp','hp.prospect_id','prospect.id')
                        ->join('sumber_platform as sp','sp.id','prospect.sumber_platform_id')
                        ->select('prospect.id','prospect.nama_prospect','prospect.hp','prospect.email','prospect.is_pin','prospect.date_pin','prospect.created_at','prospect.status_id','sp.nama_platform','prospect.catatan_admin')
                        ->where('hp.project_id', $request->project_id)
                        ->where('hp.user_id', Auth::user()->id)
                        ->with('notesAdmin')
                        ->orderBy('prospect.date_pin','desc')
                        ->orderBy('prospect.id','desc');

        if($request->id){
            $leads->join('project','project.id','hp.project_id')
                    ->join('status','status.id','prospect.status_id')
                    ->where('prospect.id',$request->id)
                    ->addSelect('project.nama_project','prospect.catatan_admin','status.status','prospect.*')
                    ->with(['historyFollowUp' => function ($query) {
                        $query->with('media');
                    }])
                    ->with('historyChangeStatus')
                    ->with('notesAdmin');
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

        foreach ($leads as $lead) {
            $lead->project = Project::find($request->project_id);
            $lead->status = Status::find($lead->status_id);

            if($request->id){
                $historyChangeStatus = $lead->historyChangeStatus;

                if (!empty($historyChangeStatus)) {
                    foreach ($historyChangeStatus as $changeStatus) {
                        $changeStatus->chat_file = Config::get('app.url').'/public/storage/ChatEvidenceFile/'.$changeStatus->chat_file;
                    }
                }
            }

        }

        return ResponseFormatter::success($leads);

    }

    public function store(Request $request){

        $prospect = Prospect::join('history_prospect as hpr','hpr.prospect_id','prospect.id')
                            ->where('prospect.hp', $request->hp)
                            ->where('prospect.is_disable',0)
                            ->where('hpr.project_id',$request->project_id)
                            ->get();

        if(count($prospect) > 0) {
            return ResponseFormatter::error(null, 'Nomor Handphone Sudah terdaftar');
        }

        $sumber_platform_id = 8;

        if ($request->verified_status == 0) {
            $sumber_platform_id = 1;
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
            'sumber_platform_id' => $sumber_platform_id,
            'accept_status' => 0,
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
            'user_id' => $sales[0]->user_id
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


        return ResponseFormatter::success(null,'Prospect berhasil diinput');

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

    public function getChangeStatus(){

        $data = [
            'status' => Status::all(),
            'reason' => Standard::all()
        ];

        return ResponseFormatter::success($data);
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

    public function addLeadsData(){
        $data = [
            'age' => Age::all(),
            'occupation' => Occupation::all(),
            'income' => Income::all(),
            'domicileProvince' => Province::all(),
            'domicileCity' => Domicile::all(),
            'workProvince' => Province::all(),
            'workCity' => WorkLocation::all()
        ];

        return ResponseFormatter::success($data);
    }

    public function destroy($id){

        $leads = Prospect::find($id);

        if ($leads) {
            if ($leads->role_by == 6) {
                $hp = HistoryProspect::where('prospect_id', $id)->delete();
                $p  = HistoryChangeStatus::where('prospect_id', $id)->delete();
                $his = HistoryInputSales::where('prospect_id', $id)->delete();
                $hi = HistoryProspectMove::where('prospect_id',$id)->delete();

                Prospect::destroy($id);

                return ResponseFormatter::success($id,'Data berhasil di hapus');
            }
        }
    }

}
