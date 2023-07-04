<?php
namespace App\Repositories\HandleError;

use ErrorException;

class ErrorsNotNumberKeys
{
    static function execute($arrays , $validator)
    {
        try
        {
            $keys = array_keys($arrays);
            foreach($keys as $key)if(!is_numeric($key))
            {
                $validator->errors()->add($key,  __("messages.Key").$key.__("messages.BeNumber"));
            }
        }catch(ErrorException $e){}
    }
}