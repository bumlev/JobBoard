<?php
namespace App\Repositories\RecruitersCtrlRepos\SearchProfile\Classes;

use App\Models\Profile;
use Illuminate\Http\Request;

class SearchProfile
{
    static public function execute(Request $request)
    {
        //Validata data 
        $data = self::ValidateData($request);
        if(gettype($data) == "object")
            return response()->json(["errorsValidation" => $data->errors()] , 404);

        // search a Profile by using a name
        $profiles = Profile::with("user")
                    ->whereHas("user" , function($query) use($data){
                        $query->where("first_name", "LIKE" , '%'.$data["name"]."%")
                        ->orWhere("last_name", "LIKE" , '%'.$data["name"]."%");
                    })->get();

        return empty(json_decode($profiles)) ? response()->json(["NoProfile" => __("messages.NoProfile")] , 404) 
        :  response()->json(["profiles" => $profiles] , 200);
    }

    static private function ValidateData(Request $request)
    {
        return ValidatorData::execute($request);
    }
}