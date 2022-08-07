<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

Route::get('products/total', [ProductController::class, 'total']);
Route::apiResource('products', ProductController::class, ['only' => ['index', 'show']]);
Route::apiResource('categories', CategoryController::class, ['only' => ['index', 'show']]);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('products', ProductController::class, ['except' => ['index', 'show']]);
    Route::apiResource('categories', CategoryController::class, ['except' => ['index', 'show']]);
});

Route::controller(AuthController::class)->prefix('auth')->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});
