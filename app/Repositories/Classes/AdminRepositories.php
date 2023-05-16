<?php
namespace App\Repositories\Classes;

use App\Repositories\Interfaces\UserRepositoriesInterface;

class AdminRepositories implements UserRepositoriesInterface
{
    static public function getUsers()
    {
        return ["Admin"];
    }
}