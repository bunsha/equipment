<?php

namespace App\Listeners\GazingleCrud;

use App\Events\GazingleCrud\ModelCreatedEvent;
use App\Http\Traits\GazingleApi;
use App\Http\Traits\GazingleConnect;
use App\Http\Traits\GazingleCrud;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ModelCreatedListener implements ShouldQueue
{

    use GazingleCrud;



    public $token = null;
    public $eventModel;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  ModelCreatedEvent  $event
     * @return void
     */
    public function handle(ModelCreatedEvent $event)
    {
        if($event->item){
            $this->eventModel = $event->item->eventModel();
            $eventModels = $this->eventModel::where('account_id', $event->account)
                ->where('name', 'create')
                ->get();

            foreach($eventModels as $model){
                $met = false;
//                foreach ($model->conditions as $condition) {
//                    switch ($condition['condition']) {
//                        case 'eq': {
//                            if ($condition['value'] == $event->item[$condition['key']]) {
//                                $met = 'eq';
//                            }
//                            break;
//                        }
//                        case 'ne': {
//                            if ($condition['value'] != $event->item[$condition['key']]) {
//                                $met = 'ne';
//                            }
//                            break;
//                        }
//                        case 'gt': {
//                            if ($condition['value'] < $event->item[$condition['key']]) {
//                                $met = 'gt';
//                            }
//                            break;
//                        }
//                        case 'lt': {
//                            if ($condition['value'] > $event->item[$condition['key']]) {
//                                $met = 'lt';
//                            }
//                            break;
//                        }
//                        case 'gte': {
//                            if ($condition['value'] <= $event->item[$condition['key']]) {
//                                $met = 'gte';
//                            }
//                            break;
//                        }
//                        case 'lte': {
//                            if ($condition['value'] >= $event->item[$condition['key']]) {
//                                $met = 'lte';
//                            }
//                            break;
//                        }
//                    }
//                }

                switch ($model->action){
                    case 'create' :{
                        Log::alert('im in create');
                        $result = $this->createFrom($model->microservice, $model->params);
                        if(is_array($result) && isset($result['data'])){

                            if($model->attach_to){
                                $params = [
                                    'service' => $this->currentMicroservice,
                                    'service_id' => $event->item->id,
                                    'user_id' => ($this->user) ? $this->user : 1,
                                ];
                                $attachResult = $this->attachTo($model->microservice, $result['data']['id'], $params);
                                //Log::alert((array)$attachResult);
                            }
                        }
                        break;
                    }
                    case 'update' :{
                        break;
                    }
                    case 'delete' :{
                        break;
                    }
                    case 'restore' :{
                        break;
                    }
                    case 'purge' :{
                        break;
                    }
                    case 'send-email' :{
                        break;
                    }
                }
            }
        }
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
        Log::alert('failed');
    }
}
