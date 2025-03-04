<?php

namespace App\Http\Requests;

use App\Models\ChatUser;
use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'message' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        $chat = $this->route('chat');

        return ChatUser::query()
            ->where('chat_id', $chat->id)
            ->where('user_id', auth()->user()->id)
            ->exists();

    }
}
