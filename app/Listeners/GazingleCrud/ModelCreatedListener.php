<?php

namespace App\Listeners\GazingleCrud;

use App\Events\GazingleCrud\ModelCreatedEvent;
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

    /**
     * Handle a job failure.
     *
     * @param  ModelCreatedEvent  $event
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(ModelCreatedEvent $event, $exception)
    {
        //
    }
}
