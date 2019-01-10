<?php

namespace App\Providers;

use App\Events\ModelCreatedEvent;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\ModelCreatedEvent' => [
            'App\Listeners\ModelCreatedListener',
        ],
        'App\Events\ModelUpdatedEvent' => [
            'App\Listeners\ModelUpdatedListener',
        ],
        'App\Events\ModelDeletedEvent' => [
            'App\Listeners\ModelDeletedListener',
        ],
        'App\Events\ModelRestoredEvent' => [
            'App\Listeners\ModelRestoredListener',
        ],
        'App\Events\ModelPurgedEvent' => [
            'App\Listeners\ModelPurgedListener',
        ],
    ];
}
