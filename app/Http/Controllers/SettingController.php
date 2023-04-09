<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Roas;

class SettingController extends Controller
{
    public function index(){
        $units = Unit::getUnits();
        $roas = Roas::getRoas();
        return view('pages.settings.index', compact('units','roas'));
    }
}
