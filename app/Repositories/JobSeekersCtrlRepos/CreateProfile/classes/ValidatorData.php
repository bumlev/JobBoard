<?php
namespace App\Repositories\JobSeekersCtrlRepos\CreateProfile\Classes;

use App\Repositories\HandleError\ErrorsNotMatchKeys;
use App\Repositories\HandleError\ErrorsNotNumberKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidatorData 
{
    static function execute(Request $request)
    {
        $data = Attributes::execute($request);
        $data_rules = Rules::execute();
        $data_customise = [
            "user_id.unique" => __("Messages.ProfileExists")
        ];

        $validator  = Validator::make($data , $data_rules , $data_customise)
        ->after(function($validator) use($request , $data){

            //Add errors message if keys of request don't match to keys of defined attributes
            ErrorsNotMatchKeys::execute($request , $data , $validator);

            //Add errors message if data some skills's keys are not numbers
            ErrorsNotNumberKeys::execute($data["skills"] , $validator);
        });
        return $validator->fails() ? $validator : $data;
    }
}