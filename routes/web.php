<?php

use App\Http\Controllers\FilesController;
use App\Http\Controllers\JobSeekersController;
use App\Http\Controllers\LanguageController;
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

Route::get('/coverage' , function(){
    return view('coverage');
});

Route::get('lang/{lang}' , [LanguageController::class , 'switchLanguage']);

//------- Middleware for setting Locale ----------------- ///

Route::middleware("setlocale")->group(function(){

    Route::post('/create_user' , [UsersController::class , "store"]);
    Route::post("/authenticate" , [SessionsController::class , "authenticate"]);
    Route::get('/logout' , [SessionsController::class , "logout"]);


//---------------------------Middleware for checking if user is authenticated---------------------//

    Route::middleware("sentinel")->group(function(){

        Route::get("storage/app/public/images/{img}" , [FilesController::class , "getStoredImage"]);

        //Routes for Users
        Route::get('/users' , [UsersController::class , "index"])
        ->middleware("allpermissions:users.index");

        Route::put('/updateUser' , [UsersController::class , "update"]);


//----------------------------------------Routes for Recruiters--------------------------------------//

        Route::get("/jobs" , [RecruitersController::class , "index"])
        ->middleware("allpermissions:jobs.index");

        Route::get("/findRightCandidates/{id}" , [RecruitersController::class , "findRightCandidates"])
        ->middleware("allpermissions:jobs.rightCandidates");

        Route::post("/postJob" , [RecruitersController::class , "postJob"])
        ->middleware("allpermissions:jobs.postJob");

        Route::get("postedJobs" , [RecruitersController::class , "postedJobs"])
        ->middleware("allpermissions:jobs.postedjobs");

        Route::get("/getProfile/{id}" , [RecruitersController::class , "getProfile"]);
        Route::post("/chatWithCandidate" , [RecruitersController::class , "chatWithCandidate"]);
        Route::post("/searchProfile" , [RecruitersController::class , "searchProfile"]);


//----------------------------------------Routes for JobSeekers---------------------------------------------//

        Route::post("/createProfile" , [JobSeekersController::class , "createProfile"])
        ->middleware("allpermissions:jobs.createProfile");

        Route::post("/searchJobs" , [JobSeekersController::class , "searchJobs"]);

        Route::get("/applyJob/{id}" , [JobSeekersController::class , "applyJob"])
        ->middleware("allpermissions:jobs.applyJob");

        Route::get("/appliedJobs" , [JobSeekersController::class , "appliedJobs"])
        ->middleware("allpermissions:jobs.appliedJobs");

        Route::get("/saveJob/{id}" , [JobSeekersController::class , "saveJob"])
        ->middleware("allpermissions:jobs.saveJob");
    });
});






