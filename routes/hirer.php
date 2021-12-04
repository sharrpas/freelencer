<?php

use App\Http\Controllers\Hirer\BidController;
use App\Http\Controllers\Hirer\ProjectController;
use Illuminate\Support\Facades\Route;


Route::prefix('/freelancer')->middleware(['auth:sanctum', 'role:hirer'])->group(function () {

    Route::prefix('/project')->group(function () {

        Route::get('/', [ProjectController::class, 'index']);
        Route::post('/', [ProjectController::class, 'store']);
        Route::get('/{project}', [ProjectController::class, 'show']);
        Route::patch('/{project}', [ProjectController::class, 'update']);
        Route::delete('/{project}', [ProjectController::class, 'destroy']);

        Route::get('/{project}/bids', [BidController::class, 'show']);
        Route::post('/{project}/user/{user}/accept', [BidController::class, 'success']);


    });
});
