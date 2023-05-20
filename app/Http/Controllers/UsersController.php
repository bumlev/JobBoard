<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    protected $userRepositoryInterface;

    public function __construct()
    {
        $this->middleware('sentinel')->except(["store" , "update"]);
        $this->middleware('allpermissions:users.index', ['only' => 'index']);
        $this->middleware('allpermissions:users.show', ['only' => 'show']);
        //$this->userRepositoryInterface = $userRepositoryInterface;
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
        $dataValidator = self::ValidateData($request); 

        if(gettype($dataValidator) == "object"){
            $errors = $dataValidator->errors();
            return json_decode($errors);
        }

        $roles = $dataValidator["roles"];
        unset($dataValidator["roles"]);

        try {
            $user = Sentinel::registerAndActivate($dataValidator);
            $user->roles()->attach($roles);
            return $user;   
        } catch (QueryException $e) {
            return response()->json( "The Email already exists !");
        }    
    }

    // Find a user 
    public function show($id)
    {
        $user = User::find($id);
        return $user;
    }

    // Update a user 
    public function update(Request $request , $id)
    {
        $user = Sentinel::findUserById($id);
        $dataValidator = self::ValidateData($request);

        if(gettype($dataValidator) == "object"){
            $errors = $dataValidator->errors();
            return json_decode($errors);
        }

        $roles = $dataValidator["roles"];
        unset($dataValidator["roles"]);

        try {
            Sentinel::update($user , $dataValidator);
            $user->roles()->sync($roles);
            return $user;
        } catch (QueryException $e) {
            return response()->json( "The Email already exists !");
        }
    }
    
    // Validate data
    static private function ValidateData($request)
    {
        $roles = array_map("intval" , $request->input("roles"));

        in_array(Role::IS_SET_ADMIN , $roles) ? 
        die("you are not allowed to set yourself as admin") : "";

        $data = [
            "email" => $request->input("email"),
            "password" => $request->input("password"),
            "first_name" => $request->input("first_name"),
            "last_name" => $request->input("last_name"),
            "roles" => $roles
        ];

        $data_rules = [
            "email" => "Required|email",
            "password" => "Required|Min:6",
            "first_name" => "Required|Min:3",
            "last_name" => "Required|Min:3",
            "roles.*" => "required|not_in:0"
        ];
        return Validator::make($data , $data_rules)->fails() ? Validator::make($data , $data_rules) : $data ;
    }
}
