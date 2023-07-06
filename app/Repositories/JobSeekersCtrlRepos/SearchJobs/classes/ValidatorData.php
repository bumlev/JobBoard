<?php
namespace App\Repositories\JobSeekersCtrlRepos\SearchJobs\Classes;

use App\Repositories\HandleError\ErrorsNotMatchKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidatorData 
{
    static function execute(Request $request)
    {
        // Validate data 
        $data = ["country"=> $request->input("country") , "title" => $request->input("title")];
        $data_rules = ["country"=> "Required|string" , "title" => "Required|string"];
        
        $validator = Validator::make($data , $data_rules)
        ->after(function($validator) use($request , $data){
            //Add errors messages if keys of request don't match to keys of defined attributes
            ErrorsNotMatchKeys::add($request , $data , $validator);
        });
 
        return $validator->fails() ? $validator : $data;
    }
}