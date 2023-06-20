<?php
namespace App\Repositories\RecruitersCtrlRepos\SearchProfile\Classes;

use App\Models\Profile;

class SearchProfile
{
    static public function execute($request)
    {
        $name = $request->input("name");
        $profiles = Profile::with("user")
                    ->whereHas("user" , function($query) use($name){
                        $query->where("first_name", "LIKE" , '%'.$name."%")
                        ->orWhere("last_name", "LIKE" , '%'.$name."%");
                    })->get();

        return $profiles;
    }
}