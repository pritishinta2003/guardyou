<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message)
    {
        $message->load('sender');
    }

    /**
     * Broadcast on a private channel scoped to the booking.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('booking.' . $this->message->booking_id),
        ];
    }

    /**
     * Shape of the data sent to the client.
     */
    public function broadcastWith(): array
    {
        return [
            'id'         => $this->message->id,
            'booking_id' => $this->message->booking_id,
            'sender_id'  => $this->message->sender_id,
            'sender'     => [
                'id'   => $this->message->sender->id,
                'name' => $this->message->sender->name,
            ],
            'message'    => $this->message->message,
            'created_at' => $this->message->created_at->toIso8601String(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
