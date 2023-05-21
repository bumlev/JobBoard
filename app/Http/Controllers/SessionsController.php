<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class SessionsController extends Controller
{

    //Authentication
    public function authenticate(Request $request)
    {
        $data = [
            "email" => $request->input("email"),
            "password" => $request->input("password")
        ];
        $authenticate = Sentinel::authenticate($data); 
        return $authenticate ? $authenticate : "Your password or email is incorrect";
    }

    // Logout
    public function logout()
    {
        Sentinel::logout(Null, true);
        return response()->json("Logged out");
    }
}
