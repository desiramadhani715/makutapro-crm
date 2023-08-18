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
use App\Http\Controllers\RoasController;
use App\Http\Controllers\AdvertiserController;
use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Session;

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
    Route::post('project/prospect-move', [ProspectController::class, 'move_prospect'])->name('prospect.move');
    Route::resource('prospect', App\Http\Controllers\ProspectController::class)->names('prospect');
    Route::resource('project', App\Http\Controllers\ProjectController::class);
    Route::resource('project.banner', BannerController::class )->parameters([
        'project' => 'id_project',
        'banner' => 'id_banner'
    ]);
    Route::resource('agent', App\Http\Controllers\AgentController::class);
    Route::resource('demografi', App\Http\Controllers\DemografiController::class);
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

    Route::post('/unit-type', [UnitController::class, 'store'])->name('unit.store');
    Route::put('/unit-type/{id}', [UnitController::class, 'update'])->name('unit.update');
    Route::get('/unit-type', [UnitController::class, 'index'])->name('unit.index');
    Route::get('/unit-type/{id}', [UnitController::class, 'show'])->name('unit.show');
    Route::delete('/unit-type/{id}', [UnitController::class, 'destroy'])->name('unit.destroy');

    Route::post('/roas', [RoasController::class, 'store'])->name('roas.store');
    Route::put('/roas/{id}', [RoasController::class, 'update'])->name('roas.update');
    Route::get('/roas', [RoasController::class, 'index'])->name('roas.index');
    Route::get('/roas/{id}', [RoasController::class, 'show'])->name('roas.show');
    Route::delete('/roas/{id}', [RoasController::class, 'destroy'])->name('roas.destroy');

    Route::post('/campaign', [CampaignController::class, 'store'])->name('campaign.store');
    Route::put('/campaign/{id}', [CampaignController::class, 'update'])->name('campaign.update');
    Route::get('/campaign', [CampaignController::class, 'index'])->name('campaign.index');
    Route::get('/campaign/{id}', [CampaignController::class, 'show'])->name('campaign.show');
    Route::delete('/campaign/{id}', [CampaignController::class, 'destroy'])->name('campaign.destroy');

    Route::post('/advertiser', [AdvertiserController::class, 'store'])->name('advertiser.store');
    Route::put('/advertiser/{id}', [AdvertiserController::class, 'update'])->name('advertiser.update');
    Route::get('/advertiser', [AdvertiserController::class, 'index'])->name('advertiser.index');
    Route::get('/advertiser/{id}', [AdvertiserController::class, 'show'])->name('advertiser.show');
    Route::delete('/advertiser/{id}', [AdvertiserController::class, 'destroy'])->name('advertiser.destroy');

});

// Role for Sales Manager
Route::group(['middleware' => ['auth', 'role:3']], function () {
    Route::match(['get', 'post'], '/sales-manager', [App\Http\Controllers\SM\DashboardController::class, 'index'])->name('sm.dashboard');


    Route::get('/loadLeadsChartSM', [App\Http\Controllers\SM\DashboardController::class, 'loadLeadsChart']);

    Route::get('sm/prospect/getall', [App\Http\Controllers\SM\ProspectController::class, 'get_all'])->name('sm.prospect.all');
    Route::resource('sm/prospect', App\Http\Controllers\SM\ProspectController::class)->names('sm.prospect');
    Route::resource('sm/sales', App\Http\Controllers\SM\SalesController::class)->names('sm.sales');

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
