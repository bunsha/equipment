<?php

namespace App\Listeners\GazingleCrud;

use App\Events\GazingleCrud\ModelUpdatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ModelUpdatedListener implements ShouldQueue
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
     * @param  ModelUpdatedEvent  $event
     * @return void
     */
    public function handle(ModelUpdatedEvent $event)
    {

    }

    /**
     * Handle a job failure.
     *
     * @param  ModelUpdatedEvent  $event
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(ModelUpdatedEvent $event, $exception)
    {
        //
    }
}
