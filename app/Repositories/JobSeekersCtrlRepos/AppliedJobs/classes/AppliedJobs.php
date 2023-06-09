<?php
namespace App\Repositories\JobSeekersCtrlRepos\AppliedJobs\Classes;

use App\Models\Job;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class AppliedJobs
{
    static public function execute()
    {
        $user = User::find(Sentinel::getUser()->id);
        $profile = $user->profile;

        if(is_null($profile))
            return response()->json(['NoProfile' => __('messages.NoProfile')] , 404);
            
        $appliedJobs = $profile->jobs()->where('apply' , Job::APPLY)->get();
        return $appliedJobs->isEmpty() ? response()->json(["NoAppliedJobs" => __("messages.NoAppliedJobs")] , 404)
        : response()->json(["appliedjobs" => $appliedJobs], 200);
    }
}