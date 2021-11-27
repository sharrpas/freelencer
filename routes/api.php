<?php

use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\HirerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum')->name('logout');
//Route::post('/change-pass',[AuthController::class, 'changePass'])->middleware('auth:sanctum')->name('changePass');

Route::prefix('/project')->middleware('auth:sanctum')->middleware('role:hirer')->group(function (){
    Route::get('/',[HirerController::class,'index']);
    Route::post('/',[HirerController::class,'store']);
    Route::get('/{project}',[HirerController::class,'show']);
    Route::patch('/{project}',[HirerController::class,'update']);
    Route::delete('/{project}',[HirerController::class,'destroy']);
});







