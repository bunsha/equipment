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
        'App\Events\GazingleCrud\ModelCreatedEvent' => [
            'App\Listeners\ModelCreatedListener',
        ],
        'App\Events\GazingleCrud\ModelUpdatedEvent' => [
            'App\Listeners\ModelUpdatedListener',
        ],
        'App\Events\GazingleCrud\ModelDeletedEvent' => [
            'App\Listeners\ModelDeletedListener',
        ],
        'App\Events\GazingleCrud\ModelRestoredEvent' => [
            'App\Listeners\ModelRestoredListener',
        ],
        'App\Events\GazingleCrud\ModelPurgedEvent' => [
            'App\Listeners\ModelPurgedListener',
        ],
    ];
}
