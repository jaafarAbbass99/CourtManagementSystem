<?php

namespace App\Providers;

use App\Gates\EnterGates\Gates;
use App\Models\Account;
use App\Models\RequiredIdeDoc;
use App\Policies\AccountPolicy;
use App\Policies\LoginPolicy;
use App\Policies\RequiredIdeDocPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [

        //Account::class => UserPolicy::class,


    ];

 
    public function boot(): void
    {
        $this->registerPolicies();

        Gates::boot();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });


        //
    }
}
