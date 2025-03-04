<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Chat;
use App\Services\ChatService;

class MessageController extends Controller
{
    public function store(Chat $chat, MessageRequest $request, ChatService $chatService)
    {
        $message = $request['message'];
        $user = auth()->user();

        $chatService->storeMessage($chat, $message, $user);

        return redirect()->to(route("chat.show", $chat))->with('success', 'Message sent!');
    }
}
