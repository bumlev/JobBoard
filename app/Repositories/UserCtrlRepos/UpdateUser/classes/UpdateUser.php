<?php
namespace App\Repositories\UserCtrlRepos\UpdateUser\Classes;

use App\Models\Role;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateUser
{ 
    // Update a user
    static public function execute(Request $request)
    {
        $data = self::ValidateData($request);
        if(gettype($data) == "object")
        {
            $errors = $data->errors();
            return $errors;
        }

        $currentUser = Sentinel::getUser();
        Sentinel::update($currentUser , $data);
        $currentUser->roles()->sync($data["roles"]);
        return $currentUser;
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
        $user = Sentinel::getUser();
        return  [
            "email" =>["Required" , "email" , Rule::unique("users")->ignore($user)],
            "password" => "Required|Min:6",
            "first_name" => "Required|Min:3",
            "last_name" => "Required|Min:3",
            "roles.*" => ["required", "numeric" , Rule::notIn([0 , Role::IS_SET_ADMIN])],
        ];
    }

     //get Customized messages
     static private function messages($validator)
     {
         $validator->after(function($validator)
         {
             $roles = $validator->getData()["roles"];
             if(in_array(Role::IS_SET_ADMIN , $roles))
             {
                 $key = array_search(Role::IS_SET_ADMIN , $roles);
                 $validator->errors()->add('roles.'.$key , __('messages.ErrorAdmin'));
             }
         });
         return $validator;
     }

    // Validate data
    static private function ValidateData(Request $request)
    {
        $data = self::attributes($request);
        $data_rules = self::rules();
        $validator = Validator::make($data , $data_rules);
        $validator = self::messages($validator);
        
        return $validator->fails() ? $validator : $data ;
    }
}