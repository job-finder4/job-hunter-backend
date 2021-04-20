<?php


use App\Http\Controllers\AuthController;
use  App\Http\Controllers\JobadApplicationController;
use App\Http\Controllers\API\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\API\V1\Auth\LogoutController;
use App\Http\Controllers\API\V1\Auth\ResetPasswordController;
use App\Http\Controllers\API\V1\Auth\VerificationController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\JobPreferenceController as JobPreferenceController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\UserImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobadApplicationManagementController;
use App\Http\Controllers\JobadController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserApplicationController;

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
//Broadcast::routes(['middleware' => 'auth:api']); // this works for my api

Route::post('users/{user}/image',[UserImageController::class,'store']);
Route::delete('users/{user}/image',[UserImageController::class,'destroy']);
Route::post('/users/{user}/job-preference', [JobPreferenceController::class, 'store']);
Route::put('/users/{user}/job-preference', [JobPreferenceController::class, 'update']);
Route::delete('/users/{user}/job-preference', [JobPreferenceController::class, 'destroy']);

Route::put('users/{user}/profile/delete-details', [UserProfileController::class, 'deleteItems']);
Route::get('/all-skills', function () {
    return response(new \App\Http\Resources\SkillCollection(\App\Models\Skill::get()));
});


Route::get('/messager', function () {
    event(new \App\Events\TestJobEvent('gobranD'));
//    broadcast(new \App\Events\TestJobEvent('daniel'))->toOthers();
});

Route::get('/notifications', [NotificationsController::class, 'index']);

Route::put('/notifications/{notification_id}', [NotificationsController::class, 'update']);

/*
 * User
 */
Route::get('user', [UserController::class, 'show']);
Route::patch('user', [UserController::class, 'update']);
Route::delete('user', [UserController::class, 'destroy']);

Route::get('/users/{user}/cvs', [CvController::class, 'index']);
Route::get('/users/{user}/profile', [UserProfileController::class, 'show']);
Route::put('/users/{user}/profile', [UserProfileController::class, 'update']);
Route::post('/users/{user}/profile', [UserProfileController::class, 'store']);
Route::apiResource('users/{user}/applications', UserApplicationController::class);

Route::get('/cvs/{cv}/download', [CvController::class, 'downloadCv']);
Route::get('/user/my-cvs', [CvController::class, 'myCvs']);
Route::apiresource('/cvs', CvController::class);

Route::apiresource('/skills', SkillController::class);

Route::get('/admin-jobads', [JobadController::class, 'getJobsForAdmin']);
Route::apiresource('/jobads', JobadController::class);
Route::get('/jobads/{jobad}/cvs', [JobadController::class, 'getJobCvs']);
Route::apiResource('/jobads/{jobad}/applications', JobadApplicationController::class);
Route::patch('jobads/{jobad}/applications/{application}/manage', [JobadApplicationManagementController::class, 'evaluate']);
Route::put('/jobads/{unapprovedJobad}/approve', [JobadController::class, 'approve']);
Route::put('jobads/{jobad}/applications/{application}/manage', [JobadApplicationManagementController::class, 'evaluate']);
Route::apiResource('/jobads/{jobad}/applications', JobadApplicationController::class);

Route::get('/myjobads', [JobadController::class, 'getCompanyJobads']);
Route::apiResource('/categories', CategoryController::class);


//Route::post('/login', [AuthController::class,'login'])->name('login');

//-------------Daniel new work
Route::prefix('auth')->group(function () {
    /*
     * Auth
     */
    Route::post('logout', [LogoutController::class, 'logout']);


//    Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])
//        ->name('verification.verify');
//
//    Route::post('verify-email/resend', [VerificationController::class, 'resend'])
//        ->name('verification.resend');
//
//    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
//        ->name('password.email');
//
//    Route::post('reset-password', [ResetPasswordController::class, 'reset'])
//        ->name('password.reset');
});


//----------------daniel----------------------------------------
Route::post('register/jobseeker', [AuthController::class, 'registerAsJobSeeker']);
Route::post('register/company', [AuthController::class, 'registerAsCompany']);
//Route::post('register/admin', [AuthController::class,'registerAsAdmin']);
