<?php

namespace App\Events;

use App\Models\Message; // Or whatever your message model is
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderChatUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __class($message)
    {
        $this->message = $message;
    }

    // Define the channel name
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('order.' . $this->message->order_id),
        ];
    }

    // Data sent to the frontend
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'message' => $this->message->body,
            'user_name' => $this->message->user->name,
            'created_at' => $this->message->created_at->toIso8601String(),
        ];
    }
}