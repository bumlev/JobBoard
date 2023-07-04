<?php
namespace App\Repositories\UserCtrlRepos\UpdateUser\Classes;

use App\Models\Role;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Validation\Rule;

class Rules
{
    static function execute():array
    {
        $user = Sentinel::getUser();
        return  [
            "email" =>["Required" , "email" , Rule::unique("users")->ignore($user)],
            "password" => "Required|Min:6",
            "first_name" => "Required|Min:3",
            "last_name" => "Required|Min:3",
            "roles" => "Required|array",
            "roles.*" => ["Required", "numeric" , Rule::notIn([0 , Role::IS_SET_ADMIN])],
        ];
    }
}