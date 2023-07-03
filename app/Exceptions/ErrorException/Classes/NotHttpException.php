<?php
namespace App\Exceptions\ErrorException\classes;

class NotHttpException
{
    static public function execute()
    {
        $errorMessage = __("messages.NotHttpException");
        $errorCode = 404;
        return response()->json([
            'error' => $errorMessage,
            'code' => $errorCode,
        ], $errorCode);
    }
}