<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function get_campaign(Request $request)
    {
        $campaign = Campaign::where(['project_id' => $request->project])->pluck(
            'nama_campaign',
            'id'
        );

        return response()->json($campaign);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaign = Campaign::getCampaign();

        return response()->json(['message' => 'get Campaign successfully.', 'data' => $campaign]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campaign = new Campaign();
        $campaign->project_id = $request->project_id;
        $campaign->nama_campaign = $request->nama_campaign;
        $campaign->save();

        return response()->json(['message' => 'Campaign saved successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaign = Campaign::find($id);
        return response()->json(['data' => $campaign]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $campaign = Campaign::find($id);
        $campaign->project_id = $request->project_id;
        $campaign->nama_campaign = $request->nama_campaign;
        $campaign->save();

        return response()->json(['msg' => 'Campaign updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaign = Campaign::where('id', $id)->delete();
        return response()->json(['msg' => 'Campaign Type deleted successfully']);
    }
}
