<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

class ChatService
{

    public function getChats(User $user): Collection
    {
        return $user->chats()
            ->with(['users'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getMessages(Chat $chat): Collection
    {
        return Message::query()
            ->with(['user','chat'])
            ->where('chat_id', $chat->id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function storeMessage(Chat $chat, $message, User $user): Message
    {
        return app(Message::class)->create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'message' => $message,
        ]);

    }

}
