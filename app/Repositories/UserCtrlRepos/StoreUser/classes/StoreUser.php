<?php
namespace App\Repositories\UserCtrlRepos\StoreUser\Classes;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class StoreUser
{
    // Store a User
    static public function execute(Request $request)
    {
        $data = self::ValidateData($request); 
        if(gettype($data) == "object"){
            $errors = $data->errors();
            return $errors;
        }

        $user = Sentinel::registerAndActivate($data);
        $user->roles()->attach($data["roles"]);
        return $user;   
    }

    // Validate data
    static private function ValidateData(Request $request)
    {
        return ValidatorData::execute($request);
    }
}