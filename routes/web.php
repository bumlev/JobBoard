<?php

use App\Http\Controllers\JobSeekersController;
use App\Http\Controllers\RecruitersController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\UsersController;
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
    return view('welcome');
});

Route::get('/users' , [UsersController::class , "index"]);
Route::get("/users/{id}" , [UsersController::class , "show"]);
Route::get("get_users" , [UsersController::class , "execute"]);
Route::get('/user/{id}' , [UsersController::class , "show"]);
Route::post('/create_user' , [UsersController::class , "store"]);
Route::put('/updateUser/{id}' , [UsersController::class , "update"]);


Route::post("/authenticate" , [SessionsController::class , "authenticate"]);
Route::get('/logout' , [SessionsController::class , "logout"]);

Route::get("/jobs" , [RecruitersController::class , "index"]);
Route::get("/findRightCandidates/{id}" , [RecruitersController::class , "findRightCandidates"]);
Route::get("/getProfile/{id}" , [RecruitersController::class , "getProfile"]);
Route::post("/postJob" , [RecruitersController::class , "postJob"]);
Route::post("/searchProfile" , [RecruitersController::class , "searchProfile"]);

Route::post("/createProfile" , [JobSeekersController::class , "createProfile"]);
Route::get("/applyJob/{id}" , [JobSeekersController::class , "applyJob"]);
Route::get("/appliedJobs" , [JobSeekersController::class , "appliedJobs"]);
Route::get("saveAppliedJob/{id}" , [JobSeekersController::class , "saveAppliedJob"]);
Route::post("searchJobs" , [JobSeekersController::class , "searchJobs"]);