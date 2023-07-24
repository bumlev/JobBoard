<?php
namespace App\Repositories\JobSeekersCtrlRepos\ApplyJob\Classes;

use App\Models\Job;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class ApplyJob
{
    static function execute($job_id)
    {
        $user = User::find(Sentinel::getUser()->id);
        $profile = $user->profile;

        if(is_null($profile))
            return response()->json(['NoProfile' => __('messages.NoProfile')] , 404);

        $profile->jobs()->syncWithoutDetaching([$job_id =>["apply" => Job::APPLY]]);
        $job = $profile->jobs()->where('job_id' , $job_id)->first();
        return response()->json(["appliedjob" => $job] , 200);
    }
}