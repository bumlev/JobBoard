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
        die;
        if(gettype($data) == "object")
        {
            $errors = $data->errors();
            return response()->json(["errorValidation" => $errors] , 422);
        } 
        $currentUser_id = Sentinel::getUser()->id;
        $currentUser = User::find($currentUser_id);

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