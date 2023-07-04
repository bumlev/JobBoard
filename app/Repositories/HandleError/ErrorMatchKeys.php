<?php
namespace App\Repositories\HandleError;

use Illuminate\Http\Request;

class ErrorMatchKeys
{
    static function execute(Request $request , $data , $validator)
    {
        $keys = array_keys(array_diff_key($request->all() , $data));
        foreach($keys as $key){
            $validator->errors()->add($key, __("messages.Key").$key.__("messages.Defined"));
        }
    }
}