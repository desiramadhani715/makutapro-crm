<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AppReminder;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Prospect;
use App\Models\Project;
use App\Helper\Helper;
use Illuminate\Support\Facades\Mail;

class AppointmentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Appointment Reminder';

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
        $schedule = AppReminder::whereDate('app_date', now()->format('Y-m-d'))
                                ->whereTime('app_time', now()->format('H:i'))
                                ->get();

        foreach ($schedule as $value) {
            $appointment = Appointment::find($value->appointment_id);
            $user = User::find($appointment->user_id);
            $prospect = Prospect::find($appointment->prospect_id);
            $project = Project::find($appointment->project_id);

            if ($user->email != null) {
                Mail::raw('Anda memiliki janji temu dengan konsumen', function ($message) {
                    $message->subject('Makutapro - Reminder');
                    $message->to($user->email);
                });
            }
            Helper::PushNotif('Appointment : '.$prospect->nama_prospect, $appointment->start_app_time.'-'.$appointment->end_app_time.', '.$project->nama_project, $user->id);
        }
    }
}
