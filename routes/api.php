<?php

use App\Http\Controllers\API\TaskController;
use App\Http\Middleware\CheckAuthUser;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [TaskController::class, 'login']);



Route::group(['middleware' => ['auth','CheckAuthUser']], function () {
    Route::get('/products', [TaskController::class, 'productList']);
    Route::post('/products/create', [TaskController::class, 'productCreate']);
});
