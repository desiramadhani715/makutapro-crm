<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Unit;
use App\Models\Roas;
use App\Models\Project;

class SettingController extends Controller
{
    public function index(){
        $units = Unit::getUnits();
        $roas = Roas::getRoas();
        $projects = Project::where('pt_id', Auth::user()->pt->id)->get();
        return view('pages.settings.index', compact('units','roas','projects'));
    }
}
