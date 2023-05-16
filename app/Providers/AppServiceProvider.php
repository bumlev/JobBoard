<?php

namespace App\Providers;

use App\Repositories\Classes\UserRepositories;
use App\Repositories\Interfaces\UserRepositoriesInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //$this->app->bind(UserRepositoriesInterface::class , UserRepositories::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
