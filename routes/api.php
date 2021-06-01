<?php

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

Route::middleware(['auth:api','json.wants.always'])->prefix('v1')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('movie', \App\Http\Controllers\Api\Vi\MovieController::class,[
        'as'=>'api.v1'
    ])->only(['show']);

    Route::apiResource('comment',\App\Http\Controllers\Api\Vi\CommentController::class, [
        'as'=>'api.v1'
    ]);
});
