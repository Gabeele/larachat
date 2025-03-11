<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatUser;
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

    public function storeChat(User $owner, Collection $members, string $name): Chat
    {
        $chat = app(Chat::class)->create([
            'user_id' => $owner->id,
            'name' => $name,
        ]);

        // Add owner
        app(ChatUser::class)->create([
            'chat_id' => $chat->id,
            'user_id' => $owner->id,
        ]);

        // Add members
        foreach ($members as $member) {
            app(ChatUser::class)->create([
                'chat_id' => $chat->id,
                'user_id' => $member->id,
            ]);
        }

        return $chat;
    }

}
