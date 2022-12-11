<?php

use App\Http\Controllers\API\BroadcastController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\ParkingController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VechileController;
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

Route::group(['middleware' => ['auth:sanctum']], function () {
    // User Route
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/users/photo', [UserController::class, 'updateAvatar']);
    Route::post('/users/password', [UserController::class, 'updatePassword']);
    Route::post('/users', [UserController::class, 'updateProfile']);
    Route::get('/users', [UserController::class, 'fetch']);
    Route::get('/user', [UserController::class, 'getUser']);

    // Vechile Route
    Route::post('/vechiles', [VechileController::class, 'store']);
    Route::post('/vechiles/update', [VechileController::class, 'update']);
    Route::delete('/vechiles', [VechileController::class, 'delete']);

    // Broadcast Route
    Route::post('/broadcasts', [BroadcastController::class, 'store']);
    Route::post('/broadcasts/update', [BroadcastController::class, 'update']);
    Route::delete('/broadcasts', [BroadcastController::class, 'delete']);

    // Parking Route
    Route::post('/parkings', [ParkingController::class, 'store']);
    Route::post('/parkings/confirm', [ParkingController::class, 'confirm']);

    // Employee Route
    Route::post('/employees', [EmployeeController::class, 'store']);
});
Route::get('/broadcasts', [BroadcastController::class, 'all']);
Route::get('/vechiles', [VechileController::class, 'all']);
Route::get('/parkings', [ParkingController::class, 'all']);

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
