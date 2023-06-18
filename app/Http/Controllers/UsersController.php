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
        $this->middleware('sentinel')->except(["store"]);
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
        $data = self::ValidateData($request);
        if(gettype($data) == "object")
        {
            $errors = $data->errors();
            return $errors;
        }
        
        $currentUser = Sentinel::getUser();
        $NewDataWithMyEmailOrNewEmail = self::ifNewDataWithMyEmailOrNewEmail($currentUser , $data["email"]);

        if($NewDataWithMyEmailOrNewEmail)
        {
            $roles = $data["roles"];
            unset($data["roles"]);
            Sentinel::update($currentUser , $data);
            $currentUser->roles()->sync($roles);
            return $currentUser;
        }else{
            return response()->json(["ErrorUpdate" => __("messages.ErrorUpdate")]);
        }
    }
    
    //Get attributes for data Validation
    static private function attributes($request):array
    {
        $roles = array_map("intval" , $request->input("roles"));

        in_array(Role::IS_SET_ADMIN , $roles) ? 
        die(__('messages.ErrorAdmin')) : "";
        return [
            "email" => $request->input("email"),
            "password" => $request->input("password"),
            "first_name" => $request->input("first_name"),
            "last_name" => $request->input("last_name"),
            "roles" => $roles
        ];
    }

    //Get Rules for data Validation
    static private function rules($request):array
    {
        $method = $request->method();
        return  [
            "email" => $method == 'POST' ? "Required|email|unique:users,email":"Required|email",
            "password" => "Required|Min:6",
            "first_name" => "Required|Min:3",
            "last_name" => "Required|Min:3",
            "roles.*" => "required|numeric|not_in:0"
        ];
    }

    // Validate data
    static private function ValidateData($request)
    {
        $data = self::attributes($request);
        $data_rules = self::rules($request);
        
        $validator = Validator::make($data , $data_rules);
        return $validator->fails() ? $validator : $data ;
    }

    // Test if you update data with your email or new email
    static private function ifNewDataWithMyEmailOrNewEmail($currentUser , $email)
    {
        $ifNewDataOfUser = User::where("email" , $email)->first();
        return !$ifNewDataOfUser || $currentUser->email == $ifNewDataOfUser->email;
    }
}
