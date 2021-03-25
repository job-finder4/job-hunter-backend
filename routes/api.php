<?php


use  App\Http\Controllers\JobadApplicationController;
use App\Http\Controllers\JobadApplicationManagementController;
use App\Http\Controllers\JobadController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\UserApplicationController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::apiresource('/jobads',JobadController::class);

Route::put('/jobads/{unapprovedJobad}/approve',[JobadController::class,'approve']);

Route::get('/myjobads',[JobadController::class,'getCompanyJobads']);

Route::apiresource('/skills',SkillController::class);

Route::get('/cvs/{cv_id}/download',[CvController::class,'downloadCv']);

Route::apiresource('/cvs',CvController::class);

Route::get('/users/{user}/profile',[UserProfileController::class,'show']);

Route::put('/users/{user}/profile',[UserProfileController::class,'update']);

Route::post('/users/{user}/profile',[UserProfileController::class,'store']);

Route::put('jobads/{jobad}/applications/{application}/manage',[JobadApplicationManagementController::class,'evaluate']);

Route::apiResource('/jobads/{jobad}/applications', JobadApplicationController::class);

Route::apiResource('users/{user}/applications',UserApplicationController::class);

