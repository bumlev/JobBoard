<?php

namespace App\Exceptions;

use App\Exceptions\ErrorException\classes\MethodErrorException;
use App\Exceptions\ErrorException\classes\ModelException;
use App\Exceptions\ErrorException\classes\NotHttpException;
use App\Exceptions\ErrorException\classes\QueryErrorException;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
   

    public function register()
    {
        $this->reportable(function (Request $request ,  Throwable $e){   
        });
    }
    
    public function render($request, Throwable $e)
    {
        // Error when Database is not connected
        if($e instanceof QueryException)
        {
            return  QueryErrorException::execute();

        // Error when Method of a route is imcompatible with method of a Model
        }else if($e instanceof MethodNotAllowedHttpException)
        {
            return MethodErrorException::execute();

        // Error when the endpoint is not found or doesn't exist
        }else if($e instanceof NotFoundHttpException) 
        {
            return NotHttpException::execute();

         //Error when the Model is not found 
        }else if($e instanceof ModelNotFoundException)
        {
            return ModelException::execute();
        }

    }
}