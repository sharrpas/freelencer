<?php

use App\Http\Controllers\Freelancer\BidController;
use App\Http\Controllers\Freelancer\ProjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('/freelancer')->middleware(['auth:sanctum', 'role:freelancer'])->group(function () {

    Route::get('/project', [ProjectController::class, 'index'])->name('freelancer-all-project');
    Route::get('/project/{project}', [ProjectController::class, 'show'])->name('freelancer-one-project');

    Route::get('/bids', [BidController::class, 'index'])->name('freelancer-all-bids');
    Route::post('/project/{project}/bid', [BidController::class, 'store'])->name('freelancer-add-bid');


});
