<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskTimelogController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('tasks', TaskController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::group(['prefix' => 'tasks', 'as' => 'tasks.'], function() {
        Route::get('/{id}/total-time', [TaskTimelogController::class, 'show'])->name('total-time');
        Route::post('/{id}/log-time', [TaskTimelogController::class, 'store'])->name('log-time');
    });
});

require __DIR__.'/auth.php';
