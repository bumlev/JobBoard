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
            return response()->json(["errorsValidation" => $data->errors()] , 422);

        $currentUser = Sentinel::getUser();
        Sentinel::update($currentUser , $data);
        $currentUser->roles()->sync($data["roles"]);
        return response()->json(["updateUser" => $currentUser] , 200);
    }

    // Validate data
    static private function ValidateData(Request $request)
    {
       return ValidatorData::execute($request);
    }
}