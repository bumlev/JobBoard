<?php
namespace App\Repositories\UserCtrlRepos\UpdateUser\Classes;

use App\Repositories\HandleError\ArrayErrors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidatorData
{
    static function execute(Request $request)
    {
        $data = self::attributes($request);
        $data_rules = self::rules();

        $customized_data = [
            "roles.*.not_in" => $request->input("roles") ? __('messages.ErrorAdmin') : 
            __("validation.not_in")
        ];

        $validator = Validator::make($data , $data_rules , $customized_data)
        ->after(function($validator) use($request , $data){

            //Add errors message if keys of request don't match to keys of defined attributes
            ArrayErrors::NotMatchKeys($request , $data , $validator);

            //Add errors message if data role's keys are not numbers
            ArrayErrors::NotNumberKeys($data["roles"] , $validator);
        });
        return $validator->fails() ? $validator : $data ;
    }

    //Get attributes
    static private function attributes(Request $request)
    {
        return Attributes::execute($request);
    }

    //Get Rules
    static private function rules()
    {
        return Rules::execute();
    }
}