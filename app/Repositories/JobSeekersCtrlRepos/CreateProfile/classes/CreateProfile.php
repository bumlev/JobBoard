<?php
namespace App\Repositories\JobSeekersCtrlRepos\CreateProfile\Classes;

use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class CreateProfile
{
    static public function execute(Request $request)
    {
        $data  = self::ValidateData($request);
        if(gettype($data) == "object")
            return response()->json(["errorValidation" => $data->errors()] , 422);
        
        $currentUser = User::find(Sentinel::getUser()->id);

        $data["cv"] = self::getUrlFile($data['cv'] , "CV");
        $data["cover_letter"] = self::getUrlFile($data['cover_letter'] , "CL");
        
        $profile  = $currentUser->profile()->create($data);
        $profile->skills()->attach($data["skills"]);
        return response()->json(["profile" => $profile] , 201);
    }
      
    // Validate data
    static private function ValidateData(Request $request)
    {
       return ValidatorData::execute($request);
    }

    // Get the Url of the file
    static private function getUrlFile($file , $ext)
    { 
        return UrlFile::execute($file , $ext);
    }
}