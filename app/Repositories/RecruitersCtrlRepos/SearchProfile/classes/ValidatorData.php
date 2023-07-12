<?php
namespace App\Repositories\RecruitersCtrlRepos\SearchProfile\Classes;

use App\Repositories\HandleError\ErrorsNotMatchKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidatorData 
{
    static function execute(Request $request)
    {
        $data = ["name" => $request->input("name")];
        $data_rules = ["name" => "Required"];
        
        $validator  = Validator::make($data , $data_rules)
        ->after(function($validator) use($request , $data){
            
            //Add errors message if keys of request don't match to keys of defined attributes
            ErrorsNotMatchKeys::add($request , $data , $validator);
        });
        return $validator->fails() ? $validator : $data;
    }
}