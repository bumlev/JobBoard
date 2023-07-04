<?php
namespace App\Repositories\JobSeekersCtrlRepos\CreateProfile\Classes;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class Attributes
{
    static function execute(Request $request):array
    {
        $currentUser = Sentinel::getUser();
        $file = $request->file();
        return [
            "education" => $request->input("education"),
            "degree_id" => $request->input("degree_id"),
            "cv" => isset($file["cv"]) ? $file["cv"] : NULL,
            "cover_letter" => isset($file["cover_letter"]) ? $file["cover_letter"] : NULL,
            "phone" => $request->input("phone"),
            "user_id" => $currentUser->id,
            "country_id" => $request->input("country_id"),
            "skills" => $request->input("skills")  
        ];
    }
}
