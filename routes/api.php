<?php

use App\Http\Controllers\AirController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Clients Routes
Route::post('/air/create', [AirController::class, 'create']);
Route::get('/air/show', [AirController::class, 'show']);
Route::get('/air/list', [AirController::class, 'list_airs']);
Route::put('/air/update/{air_id}', [AirController::class, 'update']);
Route::delete('/air/delete/{air_id}', [AirController::class, 'delete']);
