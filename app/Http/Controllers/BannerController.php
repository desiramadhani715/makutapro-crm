<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Project;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_project)
    {
        $project_name = Project::select('nama_project')->where('id',$id_project)->first();
        return view('pages.banner.create',compact('id_project', 'project_name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id_project)
    {
        $request->validate([
            'title' => 'required',
            'subtitle' => 'required',
            'banner' => 'required',
            'description' => 'required',
        ]);

        $banner = new Banner();
        $banner->project_id = $id_project;
        $banner->title = $request->title;
        $banner->subtitle = $request->subtitle;
        $banner->description = $request->description;

        if ($request->hasFile('banner')) {
            $bannerName =time() . rand(1, 100) . '.' . $request->banner->getClientOriginalExtension();
            $request->banner->storeAs('public/banner', $bannerName);
            $banner->banner = $bannerName;
        }

        $banner->save();

        return redirect()->route('project.show', $id_project)->with('success', 'Banner berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id_project, $id_banner)
    {
        $banner = Banner::findorfail($id_banner);
        $project_name = Project::select('nama_project')->where('id',$id_project)->first();
        return view('pages.banner.edit', compact('banner', 'id_project', 'project_name'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_project, $id_banner)
    {
        $request->validate([
            'title' => 'required',
            'subtitle' => 'required',
            'description' => 'required',
        ]);

        $banner = Banner::findorfail($id_banner);
        $banner->title = $request->title;
        $banner->subtitle = $request->subtitle;
        $banner->description = $request->description;

        if ($request->hasFile('banner')) {
            $old_banner = $banner->banner;
            if ($old_banner) {
                @unlink(storage_path('app/public/banner/' . $old_banner));
            }

            $bannerName = time() . rand(1, 100) . '.' . $request->banner->getClientOriginalExtension();
            $request->banner->storeAs('public/banner', $bannerName);
            $banner->banner = $bannerName;
        }

        $banner->save();

        return redirect()->route('project.show', $id_project)->with('success', 'Banner berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_project, $id_banner)
    {
        $banner = Banner::findorfail($id_banner);

        if ($banner->banner != '') {
            @unlink(storage_path('app/public/banner/' . $banner->banner));
        }

        $banner->delete();

        return redirect()->route('project.show', $id_project)->with('success', 'Banner berhasil dihapus');
    }
}
