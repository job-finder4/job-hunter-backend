<?php

use App\Http\Controllers\JobadController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::apiresource('/jobads',JobadController::class);
Route::apiresource('/skills',SkillController::class);
Route::get('/cvs/{cv_id}/download',[CvController::class,'downloadCv']);
Route::apiresource('/cvs',CvController::class);
Route::get('/users/{user}/profile',[UserProfileController::class,'show']);
Route::post('/users/{user}/profile',[UserProfileController::class,'store']);
Route::patch('/users/{user}/profile',[UserProfileController::class,'update']);
