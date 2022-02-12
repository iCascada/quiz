<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('cyrillic', function ($attribute, $value) {
            return preg_match('/[А-Яа-яЁё]/u', $value);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {}
}
