<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return "Hello API";
});

Route::get('/billing-plan', [\App\Http\Controllers\BillingController::class, 'index']);
Route::post('/activate-billing-plan', [\App\Http\Controllers\BillingController::class, 'active'])->name('activatePlan');
Route::get('/set-billing-plan', [\App\Http\Controllers\BillingController::class, 'set']);
Route::post('/cancel-plan', [\App\Http\Controllers\BillingController::class, 'cancel'])->name('cancelPlan');
