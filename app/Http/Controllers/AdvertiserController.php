<?php

namespace App\Http\Controllers;

use App\Models\Advertiser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertiserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $adv = Advertiser::getAdv();

        return response()->json(['message' => 'get Advertiser successfully.', 'data' => $adv]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $adv = new Advertiser();
        $adv->pt_id = Auth::user()->pt->id;
        $adv->advertiser = $request->advertiser;
        $adv->created_by = Auth::user()->name;
        $adv->save();

        return response()->json(['message' => 'Advertiser saved successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advertiser  $advertiser
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $adv = Advertiser::find($id);
        return response()->json(['data' => $adv]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Advertiser  $advertiser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $adv = Advertiser::find($id);
        $adv->advertiser = $request->advertiser;
        $adv->updated_by = Auth::user()->name;
        $adv->save();

        return response()->json(['message' => 'Advertiser updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Advertiser  $advertiser
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $adv = Advertiser::where('id', $id)->delete();
        return response()->json(['msg' => 'Advertiser deleted successfully']);
    }
}
