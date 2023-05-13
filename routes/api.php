<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ProspectController;
use App\Http\Controllers\API\AppointmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/reset', function () {
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    Route::get('/projects', [DashboardController::class, 'projects']);
    Route::get('/project_detail/{project_id}', [DashboardController::class, 'project_detail']);

    Route::get('/leads/{project_id}', [ProspectController::class, 'all']);
    Route::post('/leads/{project_id}', [ProspectController::class, 'store']);
    Route::post('/leads/{project_id}/update', [ProspectController::class, 'update']);
    Route::post('/fu-leads',[ProspectController::class, 'FollowUpLeads']);
    Route::post('/change-status',[ProspectController::class, 'changeStatus']);
    Route::get('/change-status', [ProspectController::class, 'getChangeStatus']);
    Route::get('/add-leads', [ProspectController::class, 'addLeadsData']);

    Route::get('/user', [UserController::class, 'user']);
    Route::get('/performance/{project_id}', [UserController::class, 'performance']);
    Route::get('/activity/{project_id}', [UserController::class, 'activity']);
    Route::get('/archieve/{project_id}', [UserController::class, 'archieve']);
    Route::get('/history/{project_id}', [UserController::class, 'history_sales']);

    Route::get('/appointment/{project_id}',[AppointmentController::class, 'index']);
    Route::post('/appointment',[AppointmentController::class, 'store']);

});
