<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElevatorController;

// 系统管理路由
Route::get('/', [ElevatorController::class, 'ledger'])->name('elevator.ledger');
Route::get('/maintenance', [ElevatorController::class, 'maintenance'])->name('elevator.maintenance');
Route::get('/warning', [ElevatorController::class, 'warning'])->name('elevator.warning');

// 资料管理路由
Route::get('/data/device', [ElevatorController::class, 'device'])->name('data.device');
Route::get('/data/prepare', [ElevatorController::class, 'prepare'])->name('data.prepare');
Route::get('/data/maintenance', [ElevatorController::class, 'maintenanceData'])->name('data.maintenance');
Route::get('/data/inspection', [ElevatorController::class, 'inspection'])->name('data.inspection');
Route::get('/data/fault', [ElevatorController::class, 'fault'])->name('data.fault');
Route::get('/data/repair', [ElevatorController::class, 'repair'])->name('data.repair');
Route::get('/data/accident', [ElevatorController::class, 'accident'])->name('data.accident');
Route::get('/data/rescue', [ElevatorController::class, 'rescue'])->name('data.rescue');
Route::get('/data/query', [ElevatorController::class, 'query'])->name('data.query');

// 文件上传下载路由
Route::post('/upload', [App\Http\Controllers\FileUploadController::class, 'upload'])->name('file.upload');
Route::get('/file/download/{id}', [App\Http\Controllers\FileUploadController::class, 'download'])->name('file.download');
Route::get('/file/{id}', [App\Http\Controllers\ElevatorController::class, 'showFile'])->name('file.show');

// 电梯管理路由
Route::get('/device/create', [ElevatorController::class, 'createDevice'])->name('device.create');
Route::post('/device/store', [ElevatorController::class, 'storeDevice'])->name('device.store');
Route::get('/device/{id}', [ElevatorController::class, 'showDevice'])->name('device.show');
Route::get('/device/{id}/edit', [ElevatorController::class, 'editDevice'])->name('device.edit');
Route::post('/device/{id}/update', [ElevatorController::class, 'updateDevice'])->name('device.update');
