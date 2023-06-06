<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;


class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware("setlocale");
    }
    
    // Authenticate as a User
    public function authenticate(Request $request)
    {
        $data = [
            "email" => $request->input("email"),
            "password" => $request->input("password")
        ];
        $authenticate = Sentinel::authenticate($data); 
        return $authenticate ? $authenticate : response()->json(['errorLogin' => __('messages.errorLogin')]);
    }

    // Logout
    public function logout()
    {
        Sentinel::logout(Null, true);
        return response()->json(['logout'=> __('messages.logout')]);
    }
}
