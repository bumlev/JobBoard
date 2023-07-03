<?php
namespace App\Exceptions\ErrorException\classes;

class ModelException
{
    static public function execute()
    {
        $errorMessage = __("messages.ModelException");
        $errorCode = 404;
        return response()->json([
            'error' => $errorMessage,
            'code' => $errorCode,
        ], $errorCode);
    }
}