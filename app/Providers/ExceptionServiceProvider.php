<?php

namespace App\Providers;

use App\Exceptions\ErrorException\classes\MethodErrorException;
use App\Exceptions\ErrorException\classes\QueryErrorException;
use App\Exceptions\ErrorException\Interfaces\ErrorExceptionInterface;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\QueryException;
use Illuminate\Support\ServiceProvider;
use Throwable;

class ExceptionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
