<?php
namespace App\Repositories\RecruitersCtrlRepos\PostedJobs\Classes;

use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class PostedJobs
{
    static public function execute()
    {
        $currentUser_id = Sentinel::getUser()->id;
        $user = User::find($currentUser_id);
        $postedJobs = $user->publishedJobs;
        return $postedJobs->isEmpty() ? response()->json(["NoPostedJobs" => __("messages.NoPostedJobs")] , 404) 
        : response()->json(["postedJobs" => $postedJobs] ,200);
    }
}