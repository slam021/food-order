<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\UserController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

Route::middleware(['auth:sanctum'])->group(function () {
    // Route::post('/create-order', function(){
    //     return 'create order';
    // })->middleware(['createOrder']);

    // Route::post('/finish-order', function(){
    //     return 'finish order';
    // })->middleware(['finishOrder']);

    //user
    Route::post('/user', [UserController::class, 'store'])->middleware(['createUser']);

    //item
    Route::get('/item', [ItemController::class, 'index']);
    Route::post('/item', [ItemController::class, 'store'])->middleware(['createUpdateItem']);
    Route::post('/item/{id}', [ItemController::class, 'update'])->middleware(['createUpdateItem']);

    //order
    Route::post('/order', [OrderController::class, 'store'])->middleware(['createOrder']);
});


