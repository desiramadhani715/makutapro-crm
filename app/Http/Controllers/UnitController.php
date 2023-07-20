<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::getUnits();

        return response()->json(['message' => 'Unit type saved successfully.', 'data' => $units]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $unitType = new Unit();
        $unitType->project_id = $request->project_id;
        $unitType->unit_name = $request->unit_name;
        $unitType->save();

        return response()->json(['message' => 'Unit type saved successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $unitType = Unit::find($id);
        return response()->json(['data' => $unitType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $unit = Unit::find($id);
        $unit->project_id = $request->project_id;
        $unit->unit_name = $request->unit_name;
        $unit->save();

        return response()->json(['msg' => 'Unit Type updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = Unit::where('id', $id)->delete();
        return response()->json(['msg' => 'Unit Type deleted successfully']);
    }
}
