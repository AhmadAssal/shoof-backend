<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WatchlistController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::apiResource('items', ItemController::class);
// Route::apiResource('watchlists', WatchlistController::class);
// Route::post('watchlist-item', [WatchlistController::class, 'addItem']);
// Route::post('remove-watchlist-item', [WatchlistController::class, 'removeItem']);

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::apiResource('items', ItemController::class);
    Route::apiResource('watchlists', WatchlistController::class);
    Route::post('watchlist-item', [WatchlistController::class, 'addItem']);
    Route::post('remove-watchlist-item', [WatchlistController::class, 'removeItem']);
    Route::post('reorder-watchlist', [WatchlistController::class, 'editItems']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('user', [UserController::class, 'getUser']);
});
Route::post('forget-password', [UserController::class, 'createForgetPasswordToken']);
Route::post('reset-password', [UserController::class, 'resetPassword']);
