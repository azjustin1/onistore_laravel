<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\APIController;
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