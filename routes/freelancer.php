<?php

use App\Http\Controllers\Freelancer\BidController;
use App\Http\Controllers\Freelancer\ProjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('/freelancer')->middleware(['auth:sanctum', 'role:freelancer'])->group(function () {

    Route::get('/project', [ProjectController::class, 'index']);
    Route::get('/project/{project}', [ProjectController::class, 'show']);

    Route::get('/project/bids', [BidController::class, 'index']);
    Route::post('/project/{project}/bids', [BidController::class, 'store']);


});
