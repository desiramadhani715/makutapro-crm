<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\DemografiController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/email', function (){
    return view('email-templates/send-otp-mail');
});

// Route::match(['get', 'post'], '/', [DashboardController::class, 'index'])->name('/')->middleware(['auth', 'role:1']);

// Role for Internal Admin
Route::group(['middleware' => ['auth', 'role:1']], function () {
    Route::match(['get', 'post'], '/developer', [DashboardController::class, 'index'])->name('/');
    Route::get('prospect/getall', [ProspectController::class, 'get_all'])->name('prospect.all');
    Route::get('project/prospect', [ProjectController::class, 'get_prospect'])->name('project.prospect');
    Route::resource('prospect', ProspectController::class)->names('prospect');
    Route::resource('project', ProjectController::class);
    Route::resource('agent', AgentController::class);
    Route::resource('demografi', DemografiController::class);
    Route::post('agent/active', [AgentController::class, 'active'])->name('agent.active');
    Route::post('agent/nonactive', [AgentController::class, 'nonactive'])->name('agent.nonactive');
    Route::get('sales/{agent_id}', [SalesController::class, 'index'])->name('sales.index');
    Route::post('sales/{agent_id}', [SalesController::class, 'store'])->name('sales.store');
    Route::put('sales/{sales}', [SalesController::class, 'update'])->name('sales.update');
    Route::post('sales/activate/{sales}', [SalesController::class, 'activateSales'])->name('sales.activate');
    Route::delete('sales/{sales}', [SalesController::class, 'destroy'])->name('sales.delete');

    Route::get('/getsales', [AgentController::class, 'getSales'])->name('agent.getsales');
    Route::get('/get_agent', [AgentController::class, 'get_agent'])->name('agent.getagent');
    Route::get('/cek_hp', [ProspectController::class, 'cek_hp']);
    Route::get('/get_campaign', [CampaignController::class, 'get_campaign']);
    Route::get('/loadLeadsChart', [DashboardController::class, 'loadLeadsChart']);

    Route::get('/settings', [SettingController::class, 'index'])->name('setting.index');
    Route::resource('unit-type', UnitController::class)->middleware(['auth']);

});

// Role for Sales Manager
Route::group(['middleware' => ['auth', 'role:3']], function () {
    Route::match(['get', 'post'], '/sales-manager', [App\Http\Controllers\SM\DashboardController::class, 'index'])->name('sm.dashboard');


    Route::get('/loadLeadsChartSM', [App\Http\Controllers\SM\DashboardController::class, 'loadLeadsChart']);

    Route::get('sm/prospect/getall', [App\Http\Controllers\SM\ProspectController::class, 'get_all'])->name('sm.prospect.all');
    Route::resource('sm/prospect', App\Http\Controllers\SM\ProspectController::class)->names('sm.prospect');

    Route::get('/sm/cek_hp', [App\Http\Controllers\SM\ProspectController::class, 'cek_hp']);

});

Route::group(['middleware' => ['auth', 'role:1,3']], function () {

    Route::get('/getkota', [DemografiController::class, 'getkota']);
    Route::get('/getstandard', [StatusController::class, 'getstandard']);

    Route::get('/historyCs', [HistoryController::class, 'historyCs']);
    Route::get('/historyMp', [HistoryController::class, 'historyMp']);
    Route::get('/historyFu', [HistoryController::class, 'historyFu']);

});

Route::get('/reset', function () {
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});



//Language Change
Route::get('lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'de', 'es','fr','pt', 'cn', 'ae'])) {
        abort(400);
    }
    Session()->put('locale', $locale);
    Session::get('locale');
    return redirect()->back();
})->name('lang');


require __DIR__.'/auth.php';
