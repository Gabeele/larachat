<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatRequest;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\User;
use App\Services\ChatService;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function store(ChatRequest $request, ChatService $chatService)
    {
        $owner = $request->user();
        $members = User::whereIn('id', $request->member_ids)->get();
        $name = $request->name;

        $chat = $chatService->storeChat($owner, $members, $name);

        return redirect()->to(route('chat.show', $chat));
    }

    public function create()
    {
        return Inertia::render('Chat/Create', [
            'users' => User::all(['id', 'name', 'email'])->where('id', '!=', auth()->id()),
        ]);
    }

    public function show(Chat $chat, ChatService $chatService)
    {
        return inertia::render('Chat/Show', [
            'chat' => ChatResource::make($chat),
            'messages' => MessageResource::collection($chatService->getMessages($chat)),
        ]);
    }

}
