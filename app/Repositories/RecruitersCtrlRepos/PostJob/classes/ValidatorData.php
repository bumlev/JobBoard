<?php
namespace App\Repositories\RecruitersCtrlRepos\PostJob\Classes;

use App\Repositories\HandleError\ArrayErrors;
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
            ArrayErrors::NotMatchKeys($request , $data , $validator);

            //Add errors message if data some skills's keys are not numbers
            ArrayErrors::NotNumberKeys($data["skills"] , $validator);

            //Add errors message if data some countries's keys are not numbers
            ArrayErrors::NotNumberKeys($data["countries"] , $validator);
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