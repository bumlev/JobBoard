<?php
namespace App\Repositories\UserCtrlRepos\UpdateUser\Classes;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class UpdateUser
{ 
    // Update a user
    static public function execute(Request $request)
    {
        $data = self::ValidateData($request);
        if(gettype($data) == "object")
        {
            $errors = $data->errors();
            return $errors;
        }

        $currentUser = Sentinel::getUser();
        Sentinel::update($currentUser , $data);
        $currentUser->roles()->sync($data["roles"]);
        return $currentUser;
    }

    // Validate data
    static private function ValidateData(Request $request)
    {
       return ValidatorData::execute($request);
    }
}