<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        App::singleton('main-title', function() {
            $user = Auth::user();

            if ($user) {
                return $user->isAdmin() ? __('pages.dashboard') : (
                $user->isModerator() ?
                    __('pages.dashboard-moderator') :
                    __('pages.dashboard-user')
                );
            }

            return null;
        });
    }
}
