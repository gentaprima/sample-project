<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\WaxController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/material', [DashboardController::class, 'getMaterial']);
Route::post('/add-material',[MaterialController::class,'addMaterial']);
Route::post('/edit-material/{id}',[MaterialController::class,'editMaterial']);
Route::get('/delete-material/{id}',[MaterialController::class,'deleteMaterial']);
Route::get('/get-material',[MaterialController::class,'getMaterial']);

Route::get('/wax-room',[DashboardController::class,'getWaxRoom']);
Route::get('/add-wax-room',[DashboardController::class,'addWaxRoom']);
Route::post('/process-add',[WaxController::class,'processAdd']);
Route::get('/get-data-subprocess/{id}',[WaxController::class,'getDataSub']);
Route::post('change-status',[WaxController::class,'changeStatus']);
Route::get('/get-data-process',[WaxController::class,'getDataProcess']);