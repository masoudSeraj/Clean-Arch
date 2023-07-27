<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([

    'middleware' => ['throttle:throttler', 'api'],
    'prefix' => 'auth',

], function ($router) {
    Route::post('/', function ($router) {
        return 'yes';
    });
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me'])->name('me');
    Route::post('register', [RegisterController::class, 'register'])->name('register')->middleware('guest:api');
    Route::post('product/{product:name}/{user?}', [ProductController::class, 'comment'])->name('comment')->middleware('auth:api');
    Route::post('products', [ProductController::class, 'list'])->name('list')->middleware('auth:api');
});
