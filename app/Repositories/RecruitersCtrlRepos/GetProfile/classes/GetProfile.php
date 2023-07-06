<?php
namespace App\Repositories\RecruitersCtrlRepos\GetProfile\classes;

use App\Models\Profile;

class GetProfile 
{
    static function execute($id)
    {
        $profile = Profile::with("user" , "skills")->find($id);
        return $profile ? response()->json(["Profile" => $profile] , 200)
        : response()->json(["NoFoundProfile" => __("messages.NoFoundProfile")] , 404);
    }
}