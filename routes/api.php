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

Route::post('/project',[HirerController::class,'store'])->middleware('auth:sanctum')->middleware('role:hirer');






//todo
//Route::get('/',function (){
//   return 'mmm';
//})->middleware('auth:sanctum')->middleware('role:freelancer');
