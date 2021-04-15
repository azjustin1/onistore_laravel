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

    //Order
    Route::get("orders", [\App\Http\Controllers\OrderController::class, "index"]);
    Route::delete("orders/{id}", [\App\Http\Controllers\OrderController::class, "destroy"]);

    // Dashboard
    Route::get("dashboard", [ProductController::class, "getNumberOfProduct"]);

    // User
    Route::get("users", [UserController::class, "index"]);
    Route::get("users/{id}", [UserController::class, "adminShow"]);
    Route::put("users/{id}", [UserController::class, "adminEdit"]);

    // Products
    Route::get("products", [ProductController::class, "adminIndex"]);
    Route::get("products/{id}", [ProductController::class, "adminShow"]);
    Route::post("products", [ProductController::class, "adminCreate"]);
    Route::delete("products/{id}", [ProductController::class, "adminDelete"]);
    Route::put("products/{id}", [ProductController::class, "adminEdit"]);


    // Image upload test
    Route::post("upload", [ProductController::class, "uploadTest"]);

    // Categories
    Route::post("categories", [CategoryController::class, "adminCreate"]);
    Route::delete("categories/{id}", [CategoryController::class, "adminDelete"]);
    Route::get("categories", [CategoryController::class, "adminIndex"]);
    Route::get("categories/{id}", [CategoryController::class, "adminShow"]);
    Route::put("categories/{id}", [CategoryController::class, "adminEdit"]);
}
);

// Those routes can be acc with admin or user account
// /api/
Route::group(["middleware" => "auth.role:admin, user"], function () {
//    Route::apiResource("products", ProductController::class);
    Route::apiResource("images", \App\Http\Controllers\ImageController::class);
    Route::apiResource("comments", \App\Http\Controllers\CommentController::class);
    Route::apiResource("ratings", \App\Http\Controllers\RatingController::class);
    Route::post("/checkout", [\App\Http\Controllers\OrderController::class, "store"]);
//    Route::apiResource("categories", CategoryController::class);
});

Route::get("products", [ProductController::class, "index"]);
Route::post("/search", [ProductController::class, "getSearch"]);
Route::apiResource("products", ProductController::class);
