<?php

namespace App\Http\Controllers;

use App\Repositories\SessionsCtrlRepos\Authenticate\Classes\Authenticate;
use App\Repositories\SessionsCtrlRepos\Logout\Classes\Logout;
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
       $response = Authenticate::execute($request);
       return $response;
    }

    // Logout
    public function logout()
    {
       $response = Logout::execute();
       return $response;
    }
}
