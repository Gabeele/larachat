<?php

namespace App\Observers;

use App\Models\ChatUser;
use App\Notifications\AddedToGroup;

class ChatUserObserver
{
    public function created(ChatUser $chatUser): void
    {
        $chatUser->user->notify(new AddedToGroup($chatUser->chat, $chatUser->user));
    }

    public function updated(ChatUser $chatUser): void
    {
    }
}
