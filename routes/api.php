<?php

use App\Http\Controllers\JobadController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\CvController;
use Illuminate\Support\Facades\Route;

Route::apiresource('/jobads',JobadController::class);
Route::apiresource('/skills',SkillController::class);
Route::get('/cvs/{cv_id}/download',[CvController::class,'downloadCv']);
Route::apiresource('/cvs',CvController::class);

