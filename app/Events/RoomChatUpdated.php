<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomChatUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // We explicitly define these as direct public properties so 
    // Laravel Echo extracts them perfectly on the frontend layout.
    public $room_id;
    public $sender_id;

    /**
     * Create a new event instance.
     * Accepts the Message model object created from RoomController.
     */
    public function __construct($message)
    {
        $this->room_id = $message->room_id;
        $this->sender_id = $message->user_id; // Maps perfectly to who sent it
    }

    /**
     * Broadcast on a simple, public channel so any logged-in user can hear it
     * without needing complex channels.php authorization.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('global-notifications'),
        ];
    }

    /**
     * Define the data structure being sent over the WebSocket pipeline.
     */
    public function broadcastWith(): array
    {
        return [
            'room_id' => $this->room_id,
            'sender_id' => $this->sender_id,
        ];
    }

    /**
     * Explicitly name the broadcast alias event to cleanly look for it 
     * using '.RoomChatUpdated' in your JavaScript.
     */
    public function broadcastAs(): string
    {
        return 'RoomChatUpdated';
    }
}