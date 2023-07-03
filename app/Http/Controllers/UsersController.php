<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserCtrlRepos\StoreUser\Classes\StoreUser;
use App\Repositories\UserCtrlRepos\UpdateUser\Classes\UpdateUser;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    //Display all Users
    public function index()
    {
        $users = User::all();
        return $users;
    }

    // Create a user
    public function store(Request $request)
    { 
        $user = StoreUser::execute($request);
        return $user;
    }

    // Update a user 
    public function update(Request $request)
    {
        $user = UpdateUser::execute($request);
        return $user;
    }
}
