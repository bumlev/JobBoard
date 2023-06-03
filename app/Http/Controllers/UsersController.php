<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware("setlocale");
        $this->middleware('sentinel')->except(["store" , "update"]);
        $this->middleware('allpermissions:users.index', ['only' => 'index']);
        $this->middleware('allpermissions:users.show', ['only' => 'show']);
    }

    //Display all Users
    public function index()
    {
        $users = User::all();
        return $users;
    }

    // Create a user
    public function store(Request $request)
    {
        $data = self::ValidateData($request); 

        if(gettype($data) == "object"){
            $errors = $data->errors();
            return $errors;
        }
        $roles = $data["roles"];
        unset($data["roles"]);

        $user = Sentinel::registerAndActivate($data);
        $user->roles()->attach($roles);
        return $user;   
    }

    // Update a user 
    public function update(Request $request )
    {
        $currentUser = Sentinel::getUser();
        $data = self::ValidateData($request);

        if(gettype($data) == "object")
        {
            $errors = $data->errors();
            return json_decode($errors);
        }
        $ifNewDataOfUser = User::where("email" , $data['email'])->first();
        $ifNewDataOfUser = json_decode($ifNewDataOfUser);

        $roles = $data["roles"];
        unset($data["roles"]);

        if(!property_exists($ifNewDataOfUser , 'email') || $currentUser->email == $ifNewDataOfUser->email)
        {
            Sentinel::update($currentUser , $data);
            $currentUser->roles()->sync($roles);
            return $currentUser;
        }else{
            return response()->json(["ErrorUpdate" => __("messages.ErrorUpdate")]);
        }
    }
    
    // Validate data
    static private function ValidateData($request)
    {
        $roles = array_map("intval" , $request->input("roles"));
        $method = $request->method();

        in_array(Role::IS_SET_ADMIN , $roles) ? 
        die(__('messages.ErrorAdmin')) : "";

        $data = [
            "email" => $request->input("email"),
            "password" => $request->input("password"),
            "first_name" => $request->input("first_name"),
            "last_name" => $request->input("last_name"),
            "roles" => $roles
        ];

        $data_rules = [
            "email" => $method == 'POST' ? "Required|email|unique:users,email":"Required|email",
            "password" => "Required|Min:6",
            "first_name" => "Required|Min:3",
            "last_name" => "Required|Min:3",
            "roles.*" => "required|not_in:0"
        ];
        return Validator::make($data , $data_rules)->fails() ? Validator::make($data , $data_rules) : $data ;
    }
}
