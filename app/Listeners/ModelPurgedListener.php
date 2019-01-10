<?php

namespace App\Listeners;

use App\Events\ExampleEvent;
use App\Events\ModelPurgedEvent;
use App\Events\ModelUpdatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ModelPurgedListener implements ShouldQueue
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
     * @param  ModelPurgedEvent  $event
     * @return void
     */
    public function handle(ModelPurgedEvent $event)
    {

    }
}
