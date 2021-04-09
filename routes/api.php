<?php

use App\Models\Product;
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

// Only admin can access those routes /api/admin/
Route::group(["prefix" => "admin", "middleware" => "auth.role:admin"], function () {
        Route::get("auth", function () {
            return json_encode(["message" => "Authorized"]);
        });

        Route::get("products", [ProductController::class, "adminIndex"]);
        Route::get("products/{id}", [ProductController::class, "adminShow"]);
        Route::delete("products/{id}", [
            ProductController::class,
            "adminDelete",
        ]);
        Route::put("products/{id}", [ProductController::class, "adminEdit"]);
        Route::get("categories", [CategoryController::class, "adminIndex"]);
        Route::get("categories/{id}", [CategoryController::class, "adminShow"]);
        Route::delete("categories/{id}", [
            CategoryController::class,
            "adminDelete",
        ]);
        Route::put("admin/categories/{id}", [
            CategoryController::class,
            "adminEdit",
        ]);

    }
);

// Those routes can be acc with admin or user account
// /api/
Route::group(["middleware" => "auth.role:admin, user"], function () {
//    Route::apiResource("products", ProductController::class);
    Route::apiResource("images", \App\Http\Controllers\ImageController::class);
    Route::apiResource("categories", CategoryController::class);
});

Route::apiResource("comments", \App\Http\Controllers\CommentController::class);
Route::apiResource("ratings", \App\Http\Controllers\RatingController::class);

Route::post("/checkout", [\App\Http\Controllers\OrderController::class, "store"]);
Route::apiResource("products", ProductController::class);
Route::get("admin/dashboard", [ProductController::class, "getNumberOfProduct"]);