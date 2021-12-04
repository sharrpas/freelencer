<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;


include 'freelancer.php';
include 'hirer.php';
include 'authentication.php';

Route::get('/', [ProjectController::class, 'index']);
Route::get('/{project}', [ProjectController::class, 'show']);




