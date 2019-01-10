<?php

namespace App\Listeners\GazingleCrud;

use App\Events\GazingleCrud\ModelPurgedEvent;
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

    /**
     * Handle a job failure.
     *
     * @param  ModelPurgedEvent  $event
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(ModelPurgedEvent $event, $exception)
    {
        //
    }
}
