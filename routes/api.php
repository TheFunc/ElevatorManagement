<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForntendAPI;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix("/video")->group(function() {
    Route::get("/VideoType", [ForntendAPI::class, "videoType"]);
    Route::get("/VideoInfo", [ForntendAPI::class, "videoInfo"]);
});
