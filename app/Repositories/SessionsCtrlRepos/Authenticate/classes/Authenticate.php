<?php
namespace App\Repositories\SessionsCtrlRepos\Authenticate\Classes;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class Authenticate
{
    static function execute(Request $request)
    {
        $data = self::ValidateData($request);
        if(gettype($data) == "object")
            return response()->json(["errorsValidation" => $data->errors()] , 422);
        
        $authenticate = Sentinel::authenticate($data); 
        return $authenticate ? response()->json(["successLogin" => $authenticate] , 200) 
        : response()->json(['errorLogin' => __('messages.errorLogin')] , 401);
    }

    static private function ValidateData(Request $request)
    {
       return ValidatorData::execute($request);
    }
}