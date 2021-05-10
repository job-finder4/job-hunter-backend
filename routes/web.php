<?php

use App\Models\Jobad;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $user = User::first();
    $user->assignRole('admin');
   return $user->roles;
//    $res = $jobad->leftJoinSub($category, 'categories', function ($join) {
//        $join->on('jobads.category_id', '=', 'categories.id');
//    });

//    $res = Skill::customizeSearchTerms('Mana Farm Cons Deve Engin Work Home Car');

//    return new \App\Http\Resources\JobadCollection($res->get()->sortByDesc(function ($job) {
//        return $job->j_score+$job->s_score+$job->c_score;
//    })->values()->forPage(1,5));

});

Route::get('/all-skills', function () {
    return response(\App\Models\Skill::get(['id','name']));
});


Route::get('/all-jobs-title', function () {

});
