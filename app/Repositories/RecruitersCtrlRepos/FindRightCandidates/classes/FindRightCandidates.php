<?php
namespace App\Repositories\RecruitersCtrlRepos\FindRightCandidates\Classes;

use App\Models\Job;
use App\Models\Profile;

class FindRightCandidates
{
    static public function execute($job_id)
    {
        $job = Job::find($job_id);
        $profiles = Profile::all();
        $matchProfiles = $job->matchProfiles($profiles);
        return $matchProfiles;
    }
}