<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElevatorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// 认证路由
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 需要登录才能访问的路由
Route::middleware('auth')->group(function () {
    // 系统管理路由
    Route::get('/', [ElevatorController::class, 'ledger'])->name('elevator.ledger');
    Route::get('/maintenance', [ElevatorController::class, 'maintenance'])->name('elevator.maintenance');
    Route::get('/warning', [ElevatorController::class, 'warning'])->name('elevator.warning');
Route::post('/maintenance/{id}/status', [ElevatorController::class, 'updateStatus'])->name('maintenance.status');
Route::delete('/maintenance/{id}', [ElevatorController::class, 'deleteMaintenance'])->name('maintenance.delete');
Route::post('/maintenance/store', [ElevatorController::class, 'storeMaintenance'])->name('maintenance.store');

    // 资料管理路由
    Route::get('/data/device', [ElevatorController::class, 'device'])->name('data.device');
    Route::get('/data/upload', [ElevatorController::class, 'upload'])->name('data.upload');
    Route::get('/data/query', [ElevatorController::class, 'query'])->name('data.query');

    // 文件上传下载路由
    Route::post('/upload', [App\Http\Controllers\FileUploadController::class, 'upload'])->name('file.upload');
    Route::get('/file/download/{id}', [App\Http\Controllers\FileUploadController::class, 'download'])->name('file.download');
    Route::get('/file/{id}', [App\Http\Controllers\ElevatorController::class, 'showFile'])->name('file.show');
    Route::post('/file/{id}/delete', [App\Http\Controllers\FileUploadController::class, 'delete'])->name('file.delete');

    // 电梯管理路由
    Route::get('/device/create', [ElevatorController::class, 'createDevice'])->name('device.create');
    Route::post('/device/store', [ElevatorController::class, 'storeDevice'])->name('device.store');
    Route::get('/device/{id}', [ElevatorController::class, 'showDevice'])->name('device.show');
    Route::get('/device/{id}/edit', [ElevatorController::class, 'editDevice'])->name('device.edit');
    Route::post('/device/{id}/update', [ElevatorController::class, 'updateDevice'])->name('device.update');

    // 校区管理路由
    Route::get('/campus', [ElevatorController::class, 'campus'])->name('campus.index');
    Route::post('/campus/store', [ElevatorController::class, 'storeCampus'])->name('campus.store');
    Route::post('/campus/{id}/delete', [ElevatorController::class, 'deleteCampus'])->name('campus.delete');

// 电梯单管理路由
Route::get('/repair-orders', [ElevatorController::class, 'repairOrders'])->name('repair.orders');
Route::post('/repair-orders/upload', [ElevatorController::class, 'uploadRepairOrder'])->name('repair.upload');
Route::get('/repair-orders/{id}/download', [ElevatorController::class, 'downloadRepairOrder'])->name('repair.download');
Route::delete('/repair-orders/{id}', [ElevatorController::class, 'deleteRepairOrder'])->name('repair.delete');

// 视频管理路由
Route::get('/video', [ElevatorController::class, 'videoIndex'])->name('video.index');
Route::get('/video/preview', [ElevatorController::class, 'videoPreview'])->name('video.preview');
Route::get('/video/create', [ElevatorController::class, 'videoCreate'])->name('video.create');
Route::post('/video/upload', [ElevatorController::class, 'videoUpload'])->name('video.upload');
Route::post('/video/upload/cover', [ElevatorController::class, 'uploadCover'])->name('video.upload.cover');
Route::post('/video/upload/single', [ElevatorController::class, 'uploadSingleVideo'])->name('video.upload.single');
Route::get('/video/group/{group}', [ElevatorController::class, 'videoGroupDetail'])->name('video.group');
Route::post('/video/{id}/delete', [ElevatorController::class, 'deleteVideoGroup'])->name('video.delete');
Route::post('/video/{id}/delete-single', [ElevatorController::class, 'deleteSingleVideo'])->name('video.delete.single');
Route::post('/video-type/store', [ElevatorController::class, 'storeVideoType'])->name('video.type.store');
Route::post('/video-type/{id}/update', [ElevatorController::class, 'updateVideoType'])->name('video.type.update');
Route::post('/video-type/{id}/delete', [ElevatorController::class, 'deleteVideoType'])->name('video.type.delete');

    // 用户管理路由
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/users/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/users/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('user.store');
    Route::post('/users/{id}/delete', [UserController::class, 'delete'])->name('user.delete');
    Route::post('/users/{id}/password', [UserController::class, 'changePassword'])->name('user.password');
});
