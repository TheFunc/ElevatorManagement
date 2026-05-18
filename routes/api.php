<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendAPI;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API V1 版本分组
Route::prefix('/v1')->group(function () {

    Route::prefix("/video")->group(function() {
        Route::get("/types", [FrontendAPI::class, "videoType"]);
        Route::get("/list", [FrontendAPI::class, "videoInfo"]);
    });

    // 图文管理API路由
    Route::prefix("/image-text")->group(function() {
        Route::get("/types", [FrontendAPI::class, "imageTextType"]);
        Route::get("/list", [FrontendAPI::class, "imageTextList"]);
        Route::get("/{id}", [FrontendAPI::class, "imageTextDetail"]);
    });

});
