<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\AppReminder;
use App\Models\Project;
use App\Models\Prospect;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $appointments = Appointment::where('user_id',Auth::user()->id)
                                    ->orderBy('appointment.id','desc');
                                    
        if ($request->has('project_id')) {
            $projectIds = explode(',', $request->input('project_id'));
            $appointments->whereIn('project_id', $projectIds);
        } 

        $appointments = $appointments->get()
                        ->map(function ($item) {
                            $item->prospect = Prospect::find($item->prospect_id, 'nama_prospect');
                            $item->project = Project::find($item->project_id,'nama_project');
                            return $item;
                        });

        return ResponseFormatter::success($appointments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->json()->all();

        // Simpan data appointment
        $appointment = new Appointment();
        $appointment->project_id = $data['project_id'];
        $appointment->prospect_id = $data['prospect_id'];
        $appointment->user_id = Auth::user()->id;
        $appointment->app_note = $data['app_note'];
        $appointment->app_location = $data['app_location'];
        $appointment->start_app_time = $data['start_app_time'];
        $appointment->end_app_time = $data['end_app_time'];
        $appointment->app_date = $data['app_date'];
        $appointment->save();

        // Simpan data reminders
        $reminders = $data['reminders'];
        foreach ($reminders as $reminder) {
            $appReminder = new AppReminder();
            $appReminder->appointment_id = $appointment->id;
            $appReminder->time_period = $reminder['time_period'];
            $appReminder->app_time = $reminder['app_time'];
            $appReminder->app_date = $reminder['app_date'];
            $appReminder->save();
        }

        return ResponseFormatter::success('Appointment berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($appointmentId)
    {
        $appointment = Appointment::with('reminders')->findOrFail($appointmentId);

        if($appointment){
            $appointment->prospect = Prospect::find($appointment->prospect_id,'nama_prospect');
            $appointment->project = Project::find($appointment->project_id,'nama_project');
        }

        return ResponseFormatter::success($appointment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $appointmentId)
    {
        $data = $request->json()->all();

        // Perbarui data appointment
        $appointment = Appointment::findOrFail($appointmentId);
        $appointment->project_id = $data['project_id'];
        $appointment->prospect_id = $data['prospect_id'];
        $appointment->user_id = Auth::user()->id;
        $appointment->app_note = $data['app_note'];
        $appointment->app_location = $data['app_location'];
        $appointment->start_app_time = $data['start_app_time'];
        $appointment->end_app_time = $data['end_app_time'];
        $appointment->app_date = $data['app_date'];
        $appointment->save();

        // Hapus semua reminder yang terkait dengan appointment ini
        AppReminder::where('appointment_id', $appointmentId)->delete();

        // Simpan data reminders yang baru
        $reminders = $data['reminders'];
        foreach ($reminders as $reminder) {
            $appReminder = new AppReminder();
            $appReminder->appointment_id = $appointmentId;
            $appReminder->time_period = $reminder['time_period'];
            $appReminder->app_time = $reminder['app_time'];
            $appReminder->app_date = $reminder['app_date'];
            $appReminder->save();
        }

        return ResponseFormatter::success('Appointment berhasil diubah');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($appointmentId)
    {
        $appointment = Appointment::find($appointmentId);

        if(!$appointment){
            return ResponseFormatter::error('Appointment '.$appointmentId.' tidak dapat ditemukan');
        }
        // Hapus appointment
        Appointment::where('id', $appointmentId)->delete();

        // Hapus semua reminder yang terkait dengan appointment ini
        AppReminder::where('appointment_id', $appointmentId)->delete();

        return ResponseFormatter::success('Appointment berhasil dihapus');
    }
}
