<?php

namespace App\Listeners\GazingleCrud;


use App\Events\GazingleCrud\ModelRestoredEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ModelRestoredListener implements ShouldQueue
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
     * @param  ModelRestoredEvent  $event
     * @return void
     */
    public function handle(ModelRestoredEvent $event)
    {

    }

    /**
     * Handle a job failure.
     *
     * @param  ModelRestoredEvent  $event
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(ModelRestoredEvent $event, $exception)
    {
        //
    }
}
