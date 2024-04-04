<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Laravel\Cashier\Cashier;
use Illuminate\Support\ServiceProvider;
use App\Models\Cashier\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

        
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

    }
}
