<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\HirerController;
use Illuminate\Support\Facades\Route;


include 'freelancer.php';

Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
//Route::post('/change-pass',[AuthController::class, 'changePass'])->middleware('auth:sanctum')->name('changePass');//todo


Route::prefix('/project')->middleware(['auth:sanctum', 'role:hirer'])->group(function () {
    Route::get('/', [HirerController::class, 'index']);
    Route::post('/', [HirerController::class, 'store']);
    Route::get('/{project}', [HirerController::class, 'show']);
    Route::patch('/{project}', [HirerController::class, 'update']);
    Route::delete('/{project}', [HirerController::class, 'destroy']);

    Route::get('/{project}/bids', [HirerController::class, 'bids']);
    Route::post('/{project}/user/{user}/accept', [HirerController::class, 'accept_bid']);
});




Route::get('/',[ProjectController::class,'index']);
Route::get('/{project}',[ProjectController::class,'show']);

