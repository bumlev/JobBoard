<?php
namespace App\Repositories\RecruitersCtrlRepos\PostJob\Classes;

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

        $validator = Validator::make($data , $data_rules)
        ->after(function($validator) use($request , $data){

            //Add errors message if keys of request don't match to keys of defined attributes
            ErrorsNotMatchKeys::execute($request , $data , $validator);

            //Add errors message if data some skills's keys are not numbers
            ErrorsNotNumberKeys::execute($data["skills"] , $validator);

            //Add errors message if data some countries's keys are not numbers
            ErrorsNotNumberKeys::execute($data["countries"] , $validator);
        });
        return $validator->fails() ? $validator : $data;
    }
}