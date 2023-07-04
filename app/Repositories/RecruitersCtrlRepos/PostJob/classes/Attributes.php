<?php
namespace App\Repositories\RecruitersCtrlRepos\PostJob\Classes;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class Attributes 
{
    static function execute(Request $request):array
    {
        $currentUser = Sentinel::getUser();
        return [
            "title" => $request->input("title"),
            "content" => $request->input("content"),
            "skills" => $request->input("skills"),
            "countries" =>  $request->input("countries"),
            "user_id" => $currentUser->id
        ];
    }
}