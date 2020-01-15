<?php

namespace App\Providers;

use App\Service\Component\General as Component;
use App\Service\CacheSystem\General as CacheSystem;
use Illuminate\Support\ServiceProvider;
use Mobile_Detect;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("deviceChecker", function () {
            return new Mobile_Detect();
        });

        $this->app->singleton(CacheSystem::$serviceName, function () {
            return new CacheSystem(cache()->getDefaultDriver());
        });

        $this->app->singleton(Component::$serviceName, function () {
            return new Component();
        });
    }
}
