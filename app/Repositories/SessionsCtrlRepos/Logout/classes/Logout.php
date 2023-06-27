<?php
namespace App\Repositories\SessionsCtrlRepos\Logout\Classes;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class Logout
{
    static public function execute()
    {
        Sentinel::logout();
        return response()->json(['logout'=> __('messages.logout')]);
    }
}