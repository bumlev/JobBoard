<?php
namespace App\Repositories\JobSeekersCtrlRepos\CreateProfile\Classes;

use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreateProfile
{
    static public function execute(Request $request)
    {
        $data  = self::ValidateData($request);
        if(gettype($data) == "object")
        {
            $errors = $data->errors();
            return $errors;
        } 
        $currentUser_id = Sentinel::getUser()->id;
        $currentUser = User::find($currentUser_id);

        $data["cv"] = self::getUrlFile($data['cv'] , "CV");
        $data["cover_letter"] = self::getUrlFile($data['cover_letter'] , "CL");
        
        $profile  = $currentUser->profile()->create($data);
        $profile->skills()->attach($data["skills"]);
        return $profile;
    }

    // Get attributes for data Validation 
    static private function attributes(Request $request):array
    {
        $currentUser = Sentinel::getUser();
        $file = $request->file();
        return [
            "education" => $request->input("education"),
            "degree_id" => $request->input("level"),
            "cv" => isset($file["cv"]) ? $file["cv"] : NULL,
            "cover_letter" => isset($file["cover_letter"]) ? $file["cover_letter"] : NULL,
            "phone" => $request->input("phone"),
            "user_id" => $currentUser->id,
            "country_id" => $request->input("country_id"),
            "skills" => $request->input("skills")  
        ];
    }
  
    // Get Rules for Validation data
    static private function rules():array
    {
        return [
            "education" => "Required|min:6",
            "degree_id" => "Required|numeric|not_in:0",
            "cv" => 'Required|mimes:jpeg,png,pdf,docx|max:2048',
            "cover_letter" => 'Required|mimes:jpeg,png,pdf,docx|max:2048',
            "phone" => "Required",
            "user_id" => "Required|unique:profiles,user_id",
            "country_id" => "Required|numeric|not_in:0",
            "skills" => "Required|array",
            "skills.*" => "Required|numeric|not_in:0"
        ];
    }
      
    // Validate data
    static private function ValidateData(Request $request)
    {
        $data = self::attributes($request);
        $data_rules = self::rules();
        $data_customise = [
            "user_id.unique" => __("Messages.ProfileExists")
        ];

        $validator  = Validator::make($data , $data_rules , $data_customise);
        return $validator->fails() ? $validator : $data;
    }

    // Get the Url of the file
    static private function getUrlFile($file , $ext)
    { 
        $currentUser = Sentinel::getUser();
        $name = $currentUser->first_name.Carbon::now()->format("YmdHisv");
        $fileName = $name."_".$ext.".".$file->getClientOriginalExtension();
        
        $path = $file->storeAs('public/images' , $fileName);
        $fileUrl = asset("storage/app/".$path); 
        return $fileUrl;
    }
}