<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Order::class => OrderPolicy::class,
        Transaction::class => TransactionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function($admin){
            return $admin->role == 'admin';
        });

        Gate::define('isOrderEditor', function($admin){
            return $admin->role == 'orderEditor';
        });

        Gate::define('isTransactionEditor', function($admin){
            return $admin->role == 'transactionEditor';
        });
    }
}
