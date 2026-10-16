<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use A17\Twill\Http\Controllers\Admin\UserController as TwillUserController;
use App\Http\Controllers\Twill\UserController as CMSUserController;
use Illuminate\Support\Facades\Event;

use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TwillUserController::class, CMSUserController::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use a view composer that runs when Twill views are rendered
        // At this point, authentication middleware has already run
        View::composer('twill::*', function ($view) {
            if (!config()->has('twill-navigation-set')) {
                config(['twill-navigation' => adminMenuHelper()]);
                config(['twill-navigation-set' => true]);
            }
        });

        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('microsoft', \SocialiteProviders\Microsoft\Provider::class);
        });

        $this->app->booted(function () {
            RateLimiter::for('forums', function (Request $request) {
                return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip());
            });
        });

        User::observe(UserObserver::class);
    }
}
