<?php

namespace App\Listeners;

use App\Events\ExampleEvent;
use App\Events\ModelCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ModelCreatedListener implements ShouldQueue
{


    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ModelCreatedEvent  $event
     * @return void
     */
    public function handle(ModelCreatedEvent $event)
    {

    }
}
