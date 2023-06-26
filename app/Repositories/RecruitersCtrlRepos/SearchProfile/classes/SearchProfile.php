<?php
namespace App\Repositories\RecruitersCtrlRepos\SearchProfile\Classes;

use App\Models\Profile;
use Illuminate\Http\Request;

class SearchProfile
{
    static public function execute(Request $request)
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