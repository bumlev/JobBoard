<?php
namespace App\Repositories\JobSeekersCtrlRepos\CreateProfile\Classes;

use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Validator;

class CreateProfile
{

    static public function execute($request)
    {
        $data  = self::ValidateData($request);
        if(gettype($data) == "object")
        {
            $errors = $data->errors();
            return $errors;
        } 

        $currentUser_id = Sentinel::getUser()->id;
        $currentUser = User::find($currentUser_id);

        $data["cv"] = self::getUrlFile($data['cv']);
        $data["cover_letter"] = self::getUrlFile($data['cover_letter']);
        
        $skills = $data["skills"];
        unset($data["skills"]);

        $profile  = $currentUser->profile()->create($data);
        $profile->skills()->attach($skills);
        return $profile;
    }

    // Get attributes for data Validation 
    static private function attributes($request):array
    {
        $currentUser = Sentinel::getUser();
        $file = $request->file();
        $fileName = $currentUser->first_name.Carbon::now()->format("YmdHisv");

        return [
            "education" => $request->input("education"),
            "degree_id" => intval($request->input("level")),
            "cv" => [
                "file"=>$file["cv"], 
                "name"=> $fileName."_CV".".".$file["cv"]->getClientOriginalExtension()
            ],
            "cover_letter" => [
                "file" =>$file["cover_letter"], 
                "name"=>$fileName."_CL".".".$file["cover_letter"]->getClientOriginalExtension()
            ],
            "phone" => $request->input("phone"),
            "user_id" => $currentUser->id,
            "country_id" => intval($request->input("country_id")),
            "skills" => array_map("intval" , $request->input("skills"))     
        ];
    }
  
    // Get Rules for Validation data
    static private function rules():array
    {
        return [
            "education" => "Required|min:6",
            "degree_id" => "Required|numeric|not_in:0",
            "cv.file" => 'required|mimes:jpeg,png,pdf,docx|max:2048',
            "cv.name" => 'required',
            "cover_letter.file" => 'required|mimes:jpeg,png,pdf,docx|max:2048',
            "cover_letter.name" => 'required',
            "phone" => "Required",
            "user_id" => "Required|unique:profiles,user_id",
            "country_id" => "Required|numeric|not_in:0",
            "skills.*" => "Required|numeric|not_in:0"
        ];
    }
      
      // Validate data
    static private function ValidateData($request)
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
    static private function getUrlFile($file)
    { 
        $path = $file["file"]->storeAs('public/images' , $file["name"]);
        $fileUrl = asset("storage/app/".$path); 
        return $fileUrl;
    }
}