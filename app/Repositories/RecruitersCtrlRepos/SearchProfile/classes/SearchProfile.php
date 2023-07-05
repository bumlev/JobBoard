<?php
namespace App\Repositories\RecruitersCtrlRepos\SearchProfile\Classes;

use App\Models\Profile;
use App\Repositories\HandleError\ErrorsNotMatchKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchProfile
{
    static public function execute(Request $request)
    {
       //Validate data
        $data = ["name" => $request->input("name")];
        $data_rules = ["name" => "Required"];
        $validator  = Validator::make($data , $data_rules)
        
        ->after(function($validator) use($request , $data){   
            //Add errors message if keys of request don't match to keys of defined attributes
            ErrorsNotMatchKeys::execute($request , $data , $validator);
        });

        if($validator->fails())
            return $validator->errors();

        // search a Profile by using a name
        $profiles = Profile::with("user")
                    ->whereHas("user" , function($query) use($data){
                        $query->where("first_name", "LIKE" , '%'.$data["name"]."%")
                        ->orWhere("last_name", "LIKE" , '%'.$data["name"]."%");
                    })->get();

        return $profiles;
    }
}