<?php
namespace App\Repositories\SessionsCtrlRepos\Authenticate\Classes;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class Authenticate
{
    static public function execute($request)
    {
        $data = [
            "email" => $request->input("email"),
            "password" => $request->input("password")
        ];
        
        $authenticate = Sentinel::authenticate($data); 
        return $authenticate ? $authenticate : response()->json(['errorLogin' => __('messages.errorLogin')] , 401);
    }
}