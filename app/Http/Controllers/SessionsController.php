<?php
namespace App\Http\Controllers;

use App\Repositories\SessionsCtrlRepos\Authenticate\Classes\Authenticate;
use App\Repositories\SessionsCtrlRepos\Logout\Classes\Logout;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
     
    // Authenticate as a User
    public function authenticate(Request $request)
    {
       return Authenticate::execute($request);
    }

    // Logout
    public function logout()
    {
       return Logout::execute();
    }
}
