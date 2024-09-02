<?php

namespace App\Providers;

use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\User\UserRepository;
use App\Repository\Eloquent\User\UserRepositoryInterface;
use App\Repository\EloquentRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //$this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        //$this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        
    }

    public function boot(): void
    {
        //
    }
}
