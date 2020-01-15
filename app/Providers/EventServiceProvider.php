<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //sql queries listener
        \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
            \Log::info(json_encode($query->sql));
            \Log::info(json_encode($query->bindings));
            \Log::info(json_encode($query->time));
            \Log::info("----");
        });
        //end
    }
}
