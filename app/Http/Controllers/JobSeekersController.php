<?php
namespace App\Http\Controllers;

use App\Repositories\JobSeekersCtrlRepos\AppliedJobs\Classes\AppliedJobs;
use App\Repositories\JobSeekersCtrlRepos\ApplyJob\Classes\ApplyJob;
use App\Repositories\JobSeekersCtrlRepos\CreateProfile\Classes\CreateProfile;
use App\Repositories\JobSeekersCtrlRepos\SaveJob\Classes\SaveJob;
use App\Repositories\JobSeekersCtrlRepos\SearchJobs\Classes\SearchJobs;
use Illuminate\Http\Request;

class JobSeekersController extends Controller
{
    // Create a profile
    public function createProfile(Request $request)
    {
        $profile = CreateProfile::execute($request);
        return $profile;
    }

    /// Search jobs 
    public function searchJobs(Request $request)
    {
        $response = SearchJobs::execute($request);
        return $response;
    }

    //Apply a job
    public function applyJob($id)
    {
        $job = ApplyJob::execute($id);
        return $job;
    }

    // Display all jobs you applied jobs
    public function appliedJobs()
    {
        $appliedJobs = AppliedJobs::execute();
        return $appliedJobs;
    }

    // Save a Job
    public function saveJob($id)
    {
       $response = SaveJob::execute($id);
       return $response;
    }
}