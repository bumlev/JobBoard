<?php
namespace App\Repositories\RecruitersCtrlRepos\ChatWithCandidate\Classes;

use App\Repositories\HandleError\ArrayErrors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidatorData 
{
    static function execute(Request $request)
    {
        $data = ["receiver_id" => $request->input("receiver_id"),
        "content" => $request->input("content")];

        $data_rules = ["receiver_id" => "Required|numeric|not_in:0",
        "content" => "Required|string"];

        $dataValidator = Validator::make($data , $data_rules)
        ->after(function($dataValidator) use($request , $data){ 
            
            //Add errors message if keys of request don't match to keys of defined attributes
            ArrayErrors::NotMatchKeys($request , $data , $dataValidator);
        }); 
        return $dataValidator->fails() ? $dataValidator : $data;
    }
}