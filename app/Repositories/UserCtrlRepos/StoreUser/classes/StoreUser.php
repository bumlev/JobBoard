<?php
namespace App\Repositories\UserCtrlRepos\StoreUser\Classes;

use App\Models\Role;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StoreUser
{
    // Store a User
    static public function execute(Request $request)
    {
        $data = self::ValidateData($request); 
        if(gettype($data) == "object"){
            $errors = $data->errors();
            return $errors;
        }

        $user = Sentinel::registerAndActivate($data);
        $user->roles()->attach($data["roles"]);
        return $user;   
    }

    //Get attributes for data Validation
    static private function attributes(Request $request):array
    {
        $roles = array_map("intval" , $request->input("roles"));
        return [
            "email" => $request->input("email"),
            "password" => $request->input("password"),
            "first_name" => $request->input("first_name"),
            "last_name" => $request->input("last_name"),
            "roles" => $roles
        ];
    }

    //Get Rules for data Validation
    static private function rules():array
    {
        return  [
            "email" => ["Required" , "email" , "unique:users,email"],
            "password" => "Required|Min:6",
            "first_name" => "Required|Min:3",
            "last_name" => "Required|Min:3",
            "roles.*" => ["required", "numeric" , Rule::notIn([0 , Role::IS_SET_ADMIN])],
        ];
    }

    // Validate data
    static private function ValidateData(Request $request)
    {
        $data = self::attributes($request);
        $data_rules = self::rules();
        $customized_data = [
            "roles.*.not_in" => in_array(Role::IS_SET_ADMIN , $data["roles"]) ? __('messages.ErrorAdmin') : 
            __("validation.not_in")
        ];

        $validator = Validator::make($data , $data_rules , $customized_data);
        return $validator->fails() ? $validator : $data ;
    }
}