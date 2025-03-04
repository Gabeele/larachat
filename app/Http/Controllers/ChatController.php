<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Services\ChatService;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function show(Chat $chat, ChatService $chatService)
    {
        return inertia('Chat/Show', [
            'chat' => ChatResource::make($chat),
            'messages' => Inertia::always(fn () => MessageResource::collection($chatService->getMessages($chat))),
        ]);
    }

    public function store(Chat $chat, MessageRequest $request, ChatService $chatService)
    {
        $message = $request['message'];
        $user = auth()->user();

        $chatService->storeMessage($chat, $message, $user);

        return to_route('chat.show', $chat);

    }
}
