<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Tests\TestCase;

class MessagesControllerTest extends TestCase
{
    public function test_it_stores_a_message()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $chat = Chat::factory()->create([
            'user_id' => $userA->id,
            'name' => 'Test Chat'
        ]);

        $chat->users()->attach($userA);
        $chat->users()->attach($userB);

        $response = $this->actingAs($userA)->post(route('message.store', $chat), ['message' => 'Hello world!'])
            ->assertRedirect(route('chat.show', $chat));

        $this->assertDatabaseHas('messages', ['message' => 'Hello world!']);
    }
}
