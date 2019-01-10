<?php

namespace App\Events\GazingleCrud;

use App\Events\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class ModelUpdatedEvent extends Event  implements ShouldQueue
{

    use InteractsWithSockets, SerializesModels;

    public $item;
    public $channel;
    public $user;
    public $account;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $item, $account = false, $user = false)
    {
        $this->item = $item;
        if($account)
            $this->account = $item->account_id;
        if($user)
            $this->user = $item->user_id;
        $this->channel = $item->getTable();
        // rest logic in here
    }

    /*
     * Broadcasting an event to specific socket channel
     * Can be account-based user-base or non-based
     * @return void
     */
    public function broadcastOn()
    {
        /*
         * Account-based and User based channel example.
         */

        if($this->account)
            return new Channel($this->channel.'-'.$this->account);

        if($this->user)
            return new Channel($this->channel.'-'.$this->user);

        return new Channel($this->channel);
    }
}
