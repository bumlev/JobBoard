<?php
namespace App\Repositories\UserCtrlRepos\StoreUser\Classes;

use Illuminate\Http\Request;

class Attributes
{
    static function execute(Request $request):array
    {
        return [
            "email" => $request->input("email"),
            "password" => $request->input("password"),
            "first_name" => $request->input("first_name"),
            "last_name" => $request->input("last_name"),
            "roles" => $request->input("roles"),
        ];
    }
}