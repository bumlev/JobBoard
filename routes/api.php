<?php

use App\Http\Controllers\SessionsController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users' , [UsersController::class , "index"]);
Route::get("/users/{id}" , [UsersController::class , "show"]);
Route::get('/user/{id}' , [UsersController::class , "show"]);
Route::post('/create_user' , [UsersController::class , "store"]);
Route::put('/updateUser/{id}' , [UsersController::class , "update"]);


Route::post("/authenticate" , [SessionsController::class , "authenticate"]);
Route::get('/logout' , [SessionsController::class , "logout"]);

Route::get("/jobs" , [RecruitersController::class , "index"]);
Route::get("/findRightCandidates/{id}" , [RecruitersController::class , "findRightCandidates"]);
Route::get("/getProfile/{id}" , [RecruitersController::class , "getProfile"]);
Route::post("/chatWithCandidate/{id}" , [RecruitersController::class , "chatWithCandidate"]);
Route::post("/postJob" , [RecruitersController::class , "postJob"]);
Route::post("/searchProfile" , [RecruitersController::class , "searchProfile"]);


Route::post("/createProfile" , [JobSeekersController::class , "createProfile"]);
Route::get("/applyJob/{id}" , [JobSeekersController::class , "applyJob"]);
Route::get("/appliedJobs" , [JobSeekersController::class , "appliedJobs"]);
Route::get("saveAppliedJob/{id}" , [JobSeekersController::class , "saveAppliedJob"]);
Route::post("searchJobs" , [JobSeekersController::class , "searchJobs"]);
