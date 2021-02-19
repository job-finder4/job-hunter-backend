<?php

use App\Http\Controllers\JobadController;
use Illuminate\Support\Facades\Route;

Route::apiresource('/jobs',JobadController::class);

