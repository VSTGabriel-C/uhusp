<?php

use App\Http\Controllers\AirController;
use Illuminate\Support\Facades\Route;

// Clients Routes
Route::post('/air/create', [AirController::class, 'create']);
Route::get('/air/show', [AirController::class, 'show']);
Route::get('/air/export_air', [AirController::class, 'export_air']);
Route::get('/air/list', [AirController::class, 'list_airs']);
Route::put('/air/update/{air_id}', [AirController::class, 'update']);
Route::delete('/air/delete/{air_id}', [AirController::class, 'delete']);
