<?php

namespace App\Listeners;

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
}
