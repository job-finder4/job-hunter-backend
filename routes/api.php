<?php

use App\Http\Controllers\JobadController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::apiresource('/jobs',JobadController::class);

Route::apiResource('/user/{user}', UserProfileController::class);
