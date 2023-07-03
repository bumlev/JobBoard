<?php
namespace App\Repositories\UserCtrlRepos\StoreUser\Classes;

use App\Models\Role;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use ErrorException;
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
        return [
            "email" => $request->input("email"),
            "password" => $request->input("password"),
            "first_name" => $request->input("first_name"),
            "last_name" => $request->input("last_name"),
            "roles" => $request->input("roles"),
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
            "roles" => "Required",
            "roles.*" => ["Required" , "numeric" , Rule::notIn([0 , Role::IS_SET_ADMIN])],
        ];
    }

    // Validate data
    static private function ValidateData(Request $request)
    {
        $data = self::attributes($request);
        $data_rules = self::rules();

        $customized_data = [
            "roles.*.not_in" => $request->input("roles") ? __('messages.ErrorAdmin') : 
            __("validation.not_in")
        ];

        $validator = Validator::make($data , $data_rules , $customized_data)
        ->after(function($validator) use($request , $data){
            if(count($request->all()) !== count($data))
            {
                $keys = array_keys(array_diff_key($request->all() , $data));
                foreach($keys as $key){
                    $validator->errors()->add($key, "This Key $key is not included");
                }
            }

            $keys = array_keys($data["roles"]);
            try
            {
                foreach($keys as $key)if(!is_numeric($key))
                {
                    $validator->errors()->add("roles", "The key ".$key." in roles must be a number.");
                }
            }catch(ErrorException $e){}

        });
        return $validator->fails() ? $validator : $data ;
    }
}