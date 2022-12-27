<?php

use App\Http\Controllers\API\BroadcastController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\ParkingController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VehicleController;
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

Route::group(['middleware' => ['auth:sanctum']], function () {
    // User Route
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/users/photo', [UserController::class, 'updateAvatar']);
    Route::post('/users/password', [UserController::class, 'updatePassword']);
    Route::post('/users', [UserController::class, 'updateProfile']);
    Route::get('/users', [UserController::class, 'fetch']);
    Route::get('/user', [UserController::class, 'getUser']);

    // Vechile Route
    Route::get('/vehicles', [VehicleController::class, 'all']);
    Route::post('/vehicles', [VehicleController::class, 'store']);
    Route::post('/vehicles/update', [VehicleController::class, 'update']);
    Route::delete('/vehicles', [VehicleController::class, 'delete']);

    // Broadcast Route
    Route::post('/broadcasts', [BroadcastController::class, 'store']);
    Route::get('/broadcasts/token', [BroadcastController::class, 'getBroadcastsByToken']);
    Route::post('/broadcasts/update', [BroadcastController::class, 'update']);
    Route::delete('/broadcasts', [BroadcastController::class, 'delete']);

    // Parking Route
    Route::get('/parkings', [ParkingController::class, 'get']);
    Route::get('/parkings/latest', [ParkingController::class, 'getLatestHistory']);
    Route::get('/parkings/out', [ParkingController::class, 'getCheckOut']);
    Route::post('/parkings', [ParkingController::class, 'store']);
    Route::post('/parkings/confirm', [ParkingController::class, 'confirm']);

    // Employee Route
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::get('/employees/auth', [EmployeeController::class, 'getCurrentEmployee']);
});

Route::get('/parkings/in/count', [ParkingController::class, 'countCheckIn']);
Route::get('/parkings/out/count', [ParkingController::class, 'countCheckOut']);

Route::get('/employees', [EmployeeController::class, 'all']);
Route::get('/broadcasts', [BroadcastController::class, 'all']);

Route::post('/login', [UserController::class, 'login']);
Route::post('/login-employee', [EmployeeController::class, 'loginEmployee']);
Route::post('/register', [UserController::class, 'register']);
