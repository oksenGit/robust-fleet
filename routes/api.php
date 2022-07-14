<?php

use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\TripController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthUserController::class, 'logout']);
    Route::post('/me', [AuthUserController::class, 'me']);
});

Route::post('/register', [AuthUserController::class, 'register']);
Route::post('/login', [AuthUserController::class, 'login']);


Route::get('/availableSeats', [TripController::class, 'availableSeats']);
Route::post('/bookSeat', [TripController::class, 'bookSeat']);
