<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Prospect;
use App\Models\Project;
use App\Models\HistoryProspect;
use App\Models\HistoryProspectMove;
use App\Models\HistoryBlast;
use App\Models\HistoryChangeStatus;
use App\Models\Historysales;
use App\Models\RemindStatus;
use App\Models\StatusLog;
use App\Models\User;
use App\Models\Agent;
use App\Models\Sales;
use Illuminate\Support\Facades\DB;
use App\Helper\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ChangeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for Status prospect and move according to the specified time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $leads = Prospect::with('historyProspect')->with('blast')->where('status_id', '!=', 6)->get();

        foreach ($leads as $prospect) {

            if ($prospect->status_id == 1 || $prospect->status_id == 7) {
                $moveDate = Carbon::parse($prospect->historyProspect->move_date);
                $twoHoursAgo = Carbon::now()->subHours(2);


                // Memeriksa apakah created_at di prospect sudah lebih dari 2 jam
                if ($moveDate->lessThanOrEqualTo($twoHoursAgo)) {

                    $project_id = $prospect->historyProspect->project_id;
                    // Mendapatkan blast terakhir dari tabel history blast
                    $lastBlast = $prospect->blast()->latest()->first();

                    $agentActive = Agent::where(['project_id' => $project_id, 'active' => 1])->orderBy('urut_agent')->get();
                    $nextSortAgent = 1;
                    if ($lastBlast) {
                        $nextSortAgent = ($lastBlast->blast_agent_id % count($agentActive)) + 1;
                    }

                    $nextAgent = $agentActive->where('urut_agent', $nextSortAgent)->first();

                    // Mengambil data sales dengan urutan sort
                    $salesActive = Sales::where(['agent_id' => $nextAgent->id, 'active' => 1])->orderBy('sort')->get();
                    $nextSortSales = 1;
                    // Mendapatkan next sort sales
                    if ($lastBlast) {
                        // Jika last sort dalam history prospect tidak kosong
                        $nextSortSales = ($lastBlast->blast_sales_id % count($salesActive)) + 1;
                    }

                    // Mendapatkan sales dengan sort yang sesuai dengan next sort
                    $nextSales = $salesActive->where('sort', $nextSortSales)->first();

                    // dd($nextAgent, $nextSales);

                    if ($lastBlast->blast_sales_id != $nextSales->sort) {
                        try {
                            DB::beginTransaction();

                            HistoryBlast::create([
                                'prospect_id' => $prospect->id,
                                'project_id' => $project_id,
                                'agent_id' => $nextAgent->id,
                                'sales_id' => $nextSales->id,
                                'blast_agent_id' => $prospect->historyProspect->blast_agent_id,
                                'blast_sales_id' => $nextSortSales,
                            ]);

                            $historyProspectMove = HistoryProspectMove::create([
                                'prospect_id' => $prospect->id,
                                'project_id' => $prospect->historyProspect->project_id,
                                'next_agent_id' => $nextAgent->id,
                                'next_sort_agent' => $nextAgent->urut_agent,
                                'prev_agent_id' => $lastBlast->agent_id,
                                'prev_sort_agent' => $lastBlast->blast_agent_id,
                                'next_sales_id' => $nextSales->id,
                                'next_sort_sales' => $nextSales->sort,
                                'prev_sales_id' => $lastBlast->sales_id,
                                'prev_sort_sales' =>$lastBlast->blast_sales_id
                            ]);

                            $historyProspect = $prospect->historyProspect;
                            $historyProspect->update([
                                'move_id' => $historyProspectMove->id,
                                'move_date' => date(now()),
                                'number_move' => $historyProspect->number_move + 1,
                                'agent_id' => $nextAgent->id,
                                'sales_id' => $nextSales->id,
                                'user_id' => $nextSales->user_id,
                                'blast_agent_id' => $nextAgent->urut_agent,
                                'blast_sales_id' => $nextSales->sort
                            ]);

                            $prospect->update([
                                'status_id' => 7,
                                'status_date' => date(now())
                            ]);

                            $user = User::find($nextSales->user_id);

                            HistoryChangeStatus::create([
                                'user_id' => $user->id,
                                'prospect_id' => $prospect->id,
                                'status_id' => 7,
                                'standard_id' => 22,
                                'role_id' => 1
                            ]);

                            HistorySales::create([
                                'project_id' => $project_id,
                                'sales_id'=> $nextSales->id,
                                'notes' => 'Kamu menerima Prospect dari sales an. '.$nextSales->nama_sales.', Follow Up sekarang!',
                                'subject' => 'Prospect : #'.$prospect->id.' - '.$prospect->nama_prospect,
                                'history_by' => 'Developer'
                            ]);

                            HistorySales::create([
                                'project_id' => $project_id,
                                'sales_id'=> $nextSales->id,
                                'notes' => 'Prospect Kamu dipindahkan ke sales an. '.$nextSales->nama_sales,
                                'subject' => 'Prospect : #'.$prospect->id.' - '.$prospect->nama_prospect,
                                'history_by' => 'Developer'
                            ]);

                            $salesPrev = Sales::find($lastBlast->sales_id);
                            HistorySales::create([
                                'project_id' => $project_id,
                                'sales_id'=> $nextSales->id,
                                'notes_dev' => $salesPrev->nama_sales." tidak follow up Prospect an. $prospect->nama_prospect, Prospect dipidahkan ke Sales an. ".$nextSales->nama_sales,
                                'subjectDev' => 'Prospect : #'.$prospect->id.' - '.$prospect->nama_prospect,
                                'history_by' => 'Sales'
                            ]);

                            DB::commit();

                            $project = Project::find($project_id);
                            $destination = '62'.substr($user->hp,1);
                            $msg = "Hallo ".strtoupper($nextSales->nama_sales)." Anda menerima database terusan dari sales lain yang belum follow up an. $prospect->nama_prospect untuk project $project->nama_project. Harap segera Follow Up database tersebut.";

                            Helper::sendWa($destination, $msg);

                            Helper::PushNotif('Prospect : #'.$prospect->id.'-'.$prospect->nama_prospect,'yeay! Kamu menerima Prospect baru, Follow Up sekarang!', $user->id);

                        } catch (\Throwable $th) {
                            $this->info($th);
                            Log::info($th);
                            DB::rollback();
                        }
                    }
                }

                $this->info('Successfully Checked Status Prospect New or Expired');
            }

            if ($prospect->status_id == 2) {

                $remindStatus = $prospect->remindStatus()->firstOrNew([]);

                if (!$remindStatus->exists) {
                    $remindStatus->sales_id = $prospect->historyProspect->sales_id;
                    $remindStatus->prospect_id = $prospect->id;
                    $remindStatus->save();
                }

                $statusDate = Carbon::parse($prospect->status_date);
                $now = Carbon::now();

                $user = User::find($prospect->historyProspect->user_id);
                $project = Project::find($prospect->historyProspect->project_id);
                $destination = '62'.substr($user->hp, 1);
                $namaProspect = strtoupper($prospect->nama_prospect);
                $namaProject = strtoupper($project->nama_project);

                if (!$remindStatus->ColdDay3) {

                    if ($statusDate->diff($now)->days >= 1 && !$remindStatus->ColdDay2) {

                        $body = "Hallo, Data ini sudah hari ke-2 belum berubah status. Harap segera update status konsumen an. $namaProspect untuk Project $namaProject jika sudah ada progress.";

                        $remindStatus->ColdDay2 = true;
                        $remindStatus->save();

                    }

                    if ($statusDate->diff($now)->days >= 2 && !$remindStatus->ColdDay3) {
                        $body = "Apakah Anda sudah follow up konsumen atas nama $namaProspect untuk Project $namaProject  yang berstatus COLD, agar mengetahui Promo dan Keunggulan Produk?";

                        $remindStatus->ColdDay3 = true;
                        $remindStatus->save();
                    }

                    try {
                        Helper::sendWa($destination, $body);
                        Helper::PushNotif('Reminder !', $body, $user->id);

                        HistorySales::create([
                            'project_id' => $project->id,
                            'sales_id'=> $prospect->historyProspect->sales_id,
                            'notes' => $body,
                            'subject' => 'Reminder !',
                            'history_by' => 'Developer'
                        ]);
                    } catch (\Throwable $th) {
                        $this->info($th);
                        Log::info($th);
                    }

                } else {
                    $prospect->update([
                        'status_id' => 6,
                        'status_date' => date(now()),
                        'edit_by' => 'Auto System'
                    ]);

                    HistoryChangeStatus::create([
                        'user_id' => $user->id,
                        'prospect_id' => $prospect->id,
                        'status_id' => 6,
                        'standard_id' => 10,
                        'role_id' => 1
                    ]);

                }
                $this->info('Successfully Checked Status Prospect Cold');
            }

            if ($prospect->status_id == 3) {

                $remindStatus = $prospect->remindStatus()->firstOrNew([]);

                if (!$remindStatus->exists) {
                    $remindStatus->sales_id = $prospect->historyProspect->sales_id;
                    $remindStatus->prospect_id = $prospect->id;
                    $remindStatus->save();
                }

                $statusDate = Carbon::parse($prospect->status_date);
                $now = Carbon::now();

                $user = User::find($prospect->historyProspect->user_id);
                $project = Project::find($prospect->historyProspect->project_id);
                $destination = '62'.substr($user->hp, 1);
                $namaProspect = strtoupper($prospect->nama_prospect);
                $namaProject = strtoupper($project->nama_project);
                $body = "Apakah Anda sudah follow up konsumen atas nama $namaProspect untuk Project $namaProject  yang berstatus WARM, agar dapat mengundang ke Marketing Gallery ?";


                if (!$remindStatus->WarmDay19) {

                    if ($statusDate->diff($now)->days >= 5 && !$remindStatus->WarmDay5) {

                        $remindStatus->WarmDay2 = true;
                        $remindStatus->save();

                    }

                    if ($statusDate->diff($now)->days >= 10 && !$remindStatus->WarmDay10) {

                        $remindStatus->WarmDay10 = true;
                        $remindStatus->save();
                    }

                    if ($statusDate->diff($now)->days >= 15 && !$remindStatus->WarmDay15) {

                        $remindStatus->WarmDay15 = true;
                        $remindStatus->save();
                    }

                    if ($statusDate->diff($now)->days >= 19 && !$remindStatus->WarmDay19) {

                        $body = "Apakah Anda sudah follow up konsumen atas nama $namaProspect untuk Project $namaProject ? Dikarenakan Sudah 19 hari berstatus WARM. untuk menghindari status berubah menjadi Not Interested secara otomatis";

                        $remindStatus->WarmDay19 = true;
                        $remindStatus->save();
                    }

                    try {
                        Helper::sendWa($destination, $body);
                        Helper::PushNotif('Reminder !', $body, $user->id);

                        HistorySales::create([
                            'project_id' => $project->id,
                            'sales_id'=> $prospect->historyProspect->sales_id,
                            'notes' => $body,
                            'subject' => 'Reminder !',
                            'history_by' => 'Developer'
                        ]);
                    } catch (\Throwable $th) {
                        $this->info($th);
                        Log::info($th);
                    }

                } else {

                    $prospect->update([
                        'status_id' => 6,
                        'status_date' => date(now()),
                        'edit_by' => 'Auto System'
                    ]);

                    HistoryChangeStatus::create([
                        'user_id' => $user->id,
                        'prospect_id' => $prospect->id,
                        'status_id' => 6,
                        'standard_id' => 11,
                        'role_id' => 1
                    ]);

                }
                $this->info('Successfully Checked Status Prospect Warm');
            }

            if ($prospect->status_id == 4) {

                $remindStatus = $prospect->remindStatus()->firstOrNew([]);

                if (!$remindStatus->exists) {
                    $remindStatus->sales_id = $prospect->historyProspect->sales_id;
                    $remindStatus->prospect_id = $prospect->id;
                    $remindStatus->save();
                }

                $statusDate = Carbon::parse($prospect->status_date);
                $now = Carbon::now();

                $user = User::find($prospect->historyProspect->user_id);
                $project = Project::find($prospect->historyProspect->project_id);
                $destination = '62'.substr($user->hp, 1);
                $namaProspect = strtoupper($prospect->nama_prospect);
                $namaProject = strtoupper($project->nama_project);
                $body = "Apakah Anda sudah follow up konsumen atas nama $namaProspect untuk Project $namaProject  yang berstatus WARM, agar dapat mengundang ke Marketing Gallery ?";


                if (!$remindStatus->HotDay19) {

                    if ($statusDate->diff($now)->days >= 5 && !$remindStatus->HotDay5) {

                        $remindStatus->HotDay2 = true;
                        $remindStatus->save();

                    }

                    if ($statusDate->diff($now)->days >= 10 && !$remindStatus->HotDay10) {

                        $remindStatus->HotDay10 = true;
                        $remindStatus->save();
                    }

                    if ($statusDate->diff($now)->days >= 15 && !$remindStatus->HotDay15) {

                        $remindStatus->HotDay15 = true;
                        $remindStatus->save();
                    }

                    if ($statusDate->diff($now)->days >= 19 && !$remindStatus->HotDay19) {

                        $body = "Apakah Anda sudah follow up konsumen atas nama $namaProspect untuk Project $namaProject ? Dikarenakan Sudah 19 hari berstatus HOT. untuk menghindari status berubah menjadi Not Interested secara otomatis";

                        $remindStatus->HotDay19 = true;
                        $remindStatus->save();
                    }

                    try {
                        Helper::sendWa($destination, $body);
                        Helper::PushNotif('Reminder !', $body, $user->id);

                        HistorySales::create([
                            'project_id' => $project->id,
                            'sales_id'=> $prospect->historyProspect->sales_id,
                            'notes' => $body,
                            'subject' => 'Reminder !',
                            'history_by' => 'Developer'
                        ]);
                    } catch (\Throwable $th) {
                        $this->info($th);
                        Log::info($th);
                    }

                } else {

                    $prospect->update([
                        'status_id' => 6,
                        'status_date' => date(now()),
                        'edit_by' => 'Auto System'
                    ]);

                    HistoryChangeStatus::create([
                        'user_id' => $user->id,
                        'prospect_id' => $prospect->id,
                        'status_id' => 6,
                        'standard_id' => 12,
                        'role_id' => 1
                    ]);

                }
                $this->info('Successfully Checked Status Prospect Hot');
            }

        }
    }
}
