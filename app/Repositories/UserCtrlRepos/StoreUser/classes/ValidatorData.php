<?php
namespace App\Repositories\UserCtrlRepos\StoreUser\Classes;

use App\Repositories\HandleError\ErrorArrays;
use App\Repositories\HandleError\ErrorMatchKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidatorData
{
    static function execute(Request $request)
    {
        $data = Attributes::execute($request);
        $data_rules = Rules::execute();

        $customized_data = [
            "roles.*.not_in" => $request->input("roles") ? __('messages.ErrorAdmin') : 
            __("validation.not_in")
        ];

        $validator = Validator::make($data , $data_rules , $customized_data)
        ->after(function($validator) use($request , $data){

            // Check if keys of request match to keys of defined attributes
            ErrorMatchKeys::execute($request , $data , $validator);

            // check if data role's keys are not letters
            ErrorArrays::execute($data["roles"] , $validator);
        });
        return $validator->fails() ? $validator : $data ;
    }
}
