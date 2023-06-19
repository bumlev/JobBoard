<?php
namespace App\Repositories\UserCtrlRepos\UpdateUser\Classes;

use App\Models\Role;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateUser
{
    
    // Update a user
    static public function execute($request)
    {
        $data = self::ValidateData($request);
        if(gettype($data) == "object")
        {
            $errors = $data->errors();
            return $errors;
        }

        $roles = $data["roles"];
        unset($data["roles"]);

        $currentUser = Sentinel::getUser();
        Sentinel::update($currentUser , $data);
        $currentUser->roles()->sync($roles);
        return $currentUser;
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
    static private function rules():array
    {
        $user = Sentinel::getUser();
        return  [
            "email" =>["Required" , "email" , Rule::unique("users")->ignore($user)],
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
        $data_rules = self::rules();
        
        $validator = Validator::make($data , $data_rules);
        return $validator->fails() ? $validator : $data ;
    }
}