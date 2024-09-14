<?php

namespace App\Providers;

use App\Models\Session;
use App\Observers\SessionObserver;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\User\UserRepository;
use App\Repository\Eloquent\User\UserRepositoryInterface;
use App\Repository\EloquentRepositoryInterface;
use App\Services\Judge\Show\ShowCasesService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Session::observe(SessionObserver::class);
    }
}
