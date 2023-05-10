<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
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
        if ($request->project_id) {
            $appointment = Appointment::where('project_id',$request->project_id)
                                        ->orderBy('appointment.id','desc')
                                        ->get()
                                        ->map(function ($item) {
                                            $item->prospect = Prospect::find($item->prospect_id, 'nama_prospect');
                                            $item->project = Project::find($item->project_id,'nama_project');
                                            return $item;
                                        });
        }

        return ResponseFormatter::success($appointment);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'project_id' => 'required',
            'prospect_id' => 'required',
            'app_time' => 'required',
            'app_date' => 'required',
        ]);

        $appointment = new Appointment();
        $appointment->project_id = $validatedData['project_id'];
        $appointment->prospect_id = $validatedData['prospect_id'];
        $appointment->user_id = Auth::user()->id;
        $appointment->app_note = $request->app_note;
        $appointment->app_location = $request->app_location;
        $appointment->app_time = $validatedData['app_time'];
        $appointment->app_date = $validatedData['app_date'];
        $appointment->save();

        return ResponseFormatter::success($appointment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
