<?php

use App\Http\Controllers\Hirer\BidController;
use App\Http\Controllers\Hirer\ProjectController;
use Illuminate\Support\Facades\Route;


Route::prefix('/hirer')->middleware(['auth:sanctum', 'role:hirer'])->group(function () {

    Route::prefix('/project')->group(function () {

        Route::get('/', [ProjectController::class, 'index'])->name('hirer-all-project');
        Route::post('/', [ProjectController::class, 'store'])->name('hirer-add-project');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('hirer-one-project');
        Route::patch('/{project}', [ProjectController::class, 'update'])->name('hirer-update-project');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('hirer-delete-project');

        Route::get('/{project}/bids', [BidController::class, 'show'])->name('hirer-one-project-bids');
        Route::post('/{project}/user/{user}/accept', [BidController::class, 'accept'])->name('hirer-accept-project-bid');

    });
});
