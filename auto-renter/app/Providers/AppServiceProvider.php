<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share role flags with every view once the user is resolved
        View::composer('*', function ($view) {
            $user = Auth::user();

            $view->with('isOwner', $user && $user->role === 'owner');
            $view->with('isCustomer', $user && $user->role === 'customer');
        });
    }
}
