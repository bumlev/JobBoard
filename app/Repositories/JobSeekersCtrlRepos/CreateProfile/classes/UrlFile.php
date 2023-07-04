<?php
namespace App\Repositories\JobSeekersCtrlRepos\CreateProfile\Classes;

use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class UrlFile
{
    static function execute($file , $ext)
    {
        $currentUser = Sentinel::getUser();
        $name = $currentUser->first_name.Carbon::now()->format("YmdHisv");
        $fileName = $name."_".$ext.".".$file->getClientOriginalExtension();
        
        $path = $file->storeAs('public/images' , $fileName);
        $fileUrl = asset("storage/app/".$path); 
        return $fileUrl;
    }
}