<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSavedEvent implements ShouldBroadcastNow
{

    public function __construct(public Message $message)
    {
    }

    public function broadcastOn(): Channel
    {
//        return new PrivateChannel('chat'.$this->message->chat_id);
        return new PrivateChannel('chat.'.$this->message->chat_id);

    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'chat_id' => $this->message->chat_id,
            'sender' => [
                'user_id' => $this->message->user_id,
                'name' => $this->message->user->name,
                'email' => $this->message->user->email,
            ],
            'created_at' => $this->message->created_at,
            'updated_at' => $this->message->updated_at,
        ];
    }
}
