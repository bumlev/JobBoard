<?php
namespace App\Repositories\HandleError;

class ErrorArrays
{
    static function execute($arrays , $validator)
    {
        if(isset($arrays) && gettype($arrays== "array"))
        {
            $keys = array_keys($arrays);
            foreach($keys as $key)if(!is_numeric($key))
            {
                $validator->errors()->add("roles",  __("messages.Key").$key.__("messages.BeNumber"));
            }
        }
    }
}