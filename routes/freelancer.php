<?php

use App\Http\Controllers\User\FreelancerController;
use Illuminate\Support\Facades\Route;

Route::prefix('/freelancer')->middleware(['auth:sanctum', 'role:freelancer'])->group(function () {

    Route::get('/my-bids', [FreelancerController::class, 'bids']);

    Route::post('/project/{project}', [FreelancerController::class, 'store']);





});
