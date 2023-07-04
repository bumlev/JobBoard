<?php
namespace App\Repositories\UserCtrlRepos\StoreUser\Classes;

use App\Models\Role;
use Illuminate\Validation\Rule;

class Rules
{
    static function execute():array
    {
        return  [
            "email" => ["Required" , "email" , "unique:users,email"],
            "password" => "Required|Min:6",
            "first_name" => "Required|Min:3",
            "last_name" => "Required|Min:3",
            "roles" => "Required|array",
            "roles.*" => ["Required" , "numeric" , Rule::notIn([0 , Role::IS_SET_ADMIN])],
        ];
    }
}