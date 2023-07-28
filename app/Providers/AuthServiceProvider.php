<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Route::group(['middleware' => ['custom_auth', 'decrypt_pin:password,username']], function () {
            Passport::tokensCan([
                'be' => 'Manage admin panel scope',
                'landing-page' => 'Manage landing page scope',
            ]);
        });

        Passport::tokensExpireIn(now()->addDays(15000));
    }
}
