<?php
namespace App\Repositories\RecruitersCtrlRepos\FindRightCandidates\Classes;

use App\Models\Job;
use App\Models\Profile;

class FindRightCandidates
{
    static public function execute($job_id)
    {
        $job = Job::find($job_id);
        if(empty(json_decode($job)))
            return response()->json(['NoJob' => __("messages.NoJob")] , 404);

        $profiles = Profile::all();
        $matchProfiles = $job->matchProfiles($profiles);
        return empty($matchProfiles) ? response()->json(["NoRightCandidates" => __("messages.NoRightCandidates")] , 404) 
        : response()->json(["matchProfiles" => $matchProfiles] , 200);
    }
}