<?php
namespace App\Repositories\Classes;

use App\Repositories\Interfaces\UserRepositoriesInterface;

class UserRepositories implements UserRepositoriesInterface
{
    static public function getUsers()
    {
        return ["Users"];
    }
}