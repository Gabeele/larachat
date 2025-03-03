<?php

namespace App\Services;

use App\Models\User;

class ChatService
{

    public function getChats(User $user)
    {
        return $user->chats()
            ->with(['user', 'users'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

}
