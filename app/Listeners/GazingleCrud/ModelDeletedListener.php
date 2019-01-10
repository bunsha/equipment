<?php

namespace App\Listeners\GazingleCrud;

use App\Events\GazingleCrud\ModelDeletedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ModelDeletedListener implements ShouldQueue
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
     * @param  ModelDeletedEvent  $event
     * @return void
     */
    public function handle(ModelDeletedEvent $event)
    {

    }

    /**
     * Handle a job failure.
     *
     * @param  ModelDeletedEvent  $event
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(ModelDeletedEvent $event, $exception)
    {
        //
    }
}
