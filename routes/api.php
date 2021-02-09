<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/station', [StationController::class, 'search']);
Route::get('/dispatch', [DispatchController::class, 'search']);
Route::post('/booking', [BookingController::class, 'booking']);
Route::get('/booking/{booking:code}', [BookingController::class, 'info']);
Route::get('/booking/{booking:code}/seat', [BookingController::class, 'getOccupiedSeats']);
Route::patch('/booking/{booking:code}/seat', [BookingController::class, 'seat']);
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user/booking', [UserController::class, 'booking']);
    Route::get('/user', [UserController::class, 'info']);
});
