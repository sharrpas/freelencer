<?php

use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
//Route::post('/change-pass',[AuthController::class, 'changePass'])->middleware('auth:sanctum')->name('changePass');//todo

