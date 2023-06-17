<?php
namespace App\Exceptions\ErrorException\classes;

class QueryErrorException
{
    static public function execute()
    {
        $errorMessage = __("messages.QueryException");
        $errorCode = 500;
        return response()->json([
            'error' => $errorMessage,
            'code' => $errorCode,
        ], $errorCode);
    }
}