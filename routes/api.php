<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\APIController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
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

Route::middleware("auth:api")->get("/user", function (Request $request) {
    return $request->user();
});

//Route::middleware('auth:api')->group(function () {
//    Route::get('/details', 'UserController@details');
//});

Route::post("signup", [UserController::class, "signup"]);
Route::post("signin", [UserController::class, "signin"]);

Route::group(["middleware" => "auth.jwt"], function () {
    Route::get("logout", "APIController@logout");
    Route::get("users", "UserController@index");
    Route::get("", function () {
        $data = ["message" => "Hello World"];
        return json_encode($data);
    });
});

Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('comments', \App\Http\Controllers\CommentController::class);
