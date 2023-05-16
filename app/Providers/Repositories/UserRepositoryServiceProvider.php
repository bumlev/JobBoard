<?php
namespace App\Providers\Repositories;

use App\Repositories\Classes\AdminRepositories;
use App\Repositories\Classes\UserRepositories;
use App\Repositories\Interfaces\UserRepositoriesInterface;
use Illuminate\Support\ServiceProvider;

class UserRepositoryServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoriesInterface::class , UserRepositories::class);
        $this->app->bind(UserRepositoriesInterface::class , AdminRepositories::class);
    }
}