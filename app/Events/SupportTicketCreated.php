<?php 
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class SupportTicketCreated implements ShouldBroadcastNow
{
    use SerializesModels;

    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function broadcastOn()
    {
        return new Channel('super-admins');
        //return new Channel('support-tickets');
    }

    public function broadcastAs()
    {
        return 'ticket.created';
    }
}
