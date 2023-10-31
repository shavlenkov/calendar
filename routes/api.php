<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Cache;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/events', [App\Http\Controllers\EventController::class, 'getAll']);
Route::get('/events/{event}', [App\Http\Controllers\EventController::class, 'findOne']);
Route::get('/rooms', [App\Http\Controllers\RoomController::class, 'getAll']);

Route::post('/events', [App\Http\Controllers\EventController::class, 'store'])->name('a');
Route::post('/rooms', [App\Http\Controllers\RoomController::class, 'store']);

Route::put('/events/{event}', [App\Http\Controllers\EventController::class, 'edit']);

Route::delete('/rooms/{room}', [App\Http\Controllers\RoomController::class, 'delete']);

Route::post('/join', [\App\Http\Controllers\EventController::class, 'join']);
Route::post('/unjoin', [\App\Http\Controllers\EventController::class, 'unjoin']);
