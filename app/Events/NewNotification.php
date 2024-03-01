<?php

namespace App\Events;

use App\Models\Notification; // Assuming Notification model is in the "App\Models" namespace
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $notification;

    /**
     * Create a new event instance.
     *
     * @param Notification $notification
     * @return void
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('notifications');
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {

        return [
            'id' => $this->notification->id,
            'user_id' => $this->notification->user_id,
            'type' => $this->notification->type,
            'data' => $this->notification->data,
            // Include any other fields you want to broadcast
        ];
    }
}
