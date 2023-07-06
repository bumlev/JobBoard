<?php
namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Profile;
use App\Repositories\RecruitersCtrlRepos\ChatWithCandidate\Classes\ChatWithCandidate;
use App\Repositories\RecruitersCtrlRepos\FindRightCandidates\Classes\FindRightCandidates;
use App\Repositories\RecruitersCtrlRepos\PostedJobs\Classes\PostedJobs;
use App\Repositories\RecruitersCtrlRepos\PostJob\Classes\PostJob;
use App\Repositories\RecruitersCtrlRepos\SearchProfile\Classes\SearchProfile;
use Illuminate\Http\Request;

class RecruitersController extends Controller
{  
    
    // Display all avalaible jobs
    public function index()
    {
       $jobs = Job::with("skills" , "countries")->get();
       return !empty($jobs) ?  response()->json(["jobs" => $jobs] , 200) : 
       response()->json(['NoJobs' => __("messages.NoJobs")] , 404);
    }

    //post a job openings
    public function  postJob(Request $request)
    {
        $job = PostJob::execute($request);
        return $job;
    }

    // Display all postedJobs
    public function postedJobs()
    {
        $postedJobs = PostedJobs::execute();
        return $postedJobs;
    }

    // find the right Candidates for a jobs
    public function findRightCandidates($job_id)
    {
        $matchProfiles = FindRightCandidates::execute($job_id);
        return $matchProfiles;
    }

    // Search a Candidate Profile
    public function searchProfile(Request $request)
    {
        $profiles = SearchProfile::execute($request);
        return $profiles;
    }

    // Get a profile
    public function getProfile($id)
    {
        $profile = Profile::with("user" , "skills")->find($id);
        return $profile ? response()->json(["Profile" => $profile])
        : response()->json(["NoFoundProfile" => __("messages.NoFoundProfile")] , 404);
    }

    /// chat with a candidate
    public function chatWithCandidate(Request $request)
    {
        $message = ChatWithCandidate::execute($request);
        return $message;
    }
}
