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
        $data = self::attributes($request);
        $data_rules = self::rules();

        $validator = Validator::make($data , $data_rules)
        ->after(function($validator) use($request , $data){

            //Add errors message if keys of request don't match to keys of defined attributes
            ErrorsNotMatchKeys::add($request , $data , $validator);

            //Add errors message if data some skills's keys are not numbers
            ErrorsNotNumberKeys::add($data["skills"] , $validator);

            //Add errors message if data some countries's keys are not numbers
            ErrorsNotNumberKeys::add($data["countries"] , $validator);
        });
        return $validator->fails() ? $validator : $data;
    }
    

    // Get Data attributes
    static private function attributes(Request $request)
    {
        return Attributes::execute($request);
    }

    //Get Data rules
    static private function rules()
    {
        return Rules::execute();
    }
}