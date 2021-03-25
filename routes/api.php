<?php

use App\Http\Controllers\API\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\API\V1\Auth\LogoutController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\Auth\ResetPasswordController;
use App\Http\Controllers\API\V1\Auth\VerificationController;
use App\Http\Controllers\API\V1\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\JobadApplicationController;
use App\Http\Controllers\JobadApplicationManagementController;
use App\Http\Controllers\JobadController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    /*
     * Auth
     */
    Route::post('logout', [LogoutController::class, 'logout']);

    Route::post('register', [RegisterController::class, 'register']);

    Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])
    ->name('verification.verify');

    Route::post('verify-email/resend', [VerificationController::class, 'resend'])
    ->name('verification.resend');

    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

    Route::post('reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.reset');

    /*
     * User
     */
    Route::get('user', [UserController::class, 'show']);

    Route::patch('user', [UserController::class, 'update']);

    Route::delete('user', [UserController::class, 'destroy']);
});



Route::apiresource('/jobads', JobadController::class);
Route::apiresource('/skills', SkillController::class);
Route::get('/cvs/{cv_id}/download', [CvController::class, 'downloadCv']);
Route::apiresource('/cvs', CvController::class);

Route::apiResource('/user/{user}/profile', UserProfileController::class);

Route::apiResource('/jobads/{jobad}/applications', JobadApplicationController::class);

Route::patch('jobads/{jobad}/applications/{application}/manage', [JobadApplicationManagementController::class, 'evaluate']);


//-------------Daniel new work
Route::get('/users/{user}/cvs', [CvController::class, 'index']);
