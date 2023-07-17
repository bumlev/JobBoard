<?php
namespace App\Repositories\HandleError;

use Illuminate\Http\Request;

class ArrayErrors
{
    static function NotMatchKeys(Request $request , $data , $validator)
    {
        $keys = array_keys(array_diff_key($request->all() , $data));
        foreach($keys as $key){
            $validator->errors()->add($key, __("messages.Key").$key.__("messages.Defined"));
        }
    }

    static function NotNumberKeys($array , $validator)
    {
        ErrorsNotNumberKeys::add($array , $validator);
    }
}