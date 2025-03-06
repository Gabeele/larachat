<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ChatTest extends TestCase
{

    public function test_it_can_get_chats_from_the_dashboard(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $chat = Chat::factory()->create([
            'user_id' => $userA->id,
        ]);

        $chat->users()->attach($userA);
        $chat->users()->attach($userB);

        $response = $this->actingAs($userA)->get(route('dashboard'));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard')
            ->has('chats.data', 1, fn (AssertableInertia $page) => $page
                ->where('id', $chat->id) // Assert the chat ID
                ->where('name', $chat->name) // Assert the chat name
                ->where('owner.id', $userA->id) // Assert the owner's ID
                ->has('members.0', 2, fn (AssertableInertia $page) => $page // Assert the first member
                ->where('id', $userA->id)
                    ->where('name', $userA->name)
                    ->where('email', $userA->email)
                )
                ->has('created_at')
                ->has('updated_at')
            )
        );

        $response->assertStatus(200);
    }

    public function test_it_can_display_chat_page()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $chat = Chat::factory()->create([
            'user_id' => $userA->id,
            'name' => 'Test Chat'
        ]);

        $chat->users()->attach($userA);
        $chat->users()->attach($userB);

        $message = Message::factory()->create([
            'chat_id' => $chat->id,
            'user_id' => $userA->id,
            'message' => 'Hello World'
        ]);

        $response = $this->actingAs($userA)->get(route('chat.show', $chat));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Chat/Show')
            ->has('chat.data', fn (AssertableInertia $page) => $page
                ->where('id', $chat->id)
                ->where('name', 'Test Chat')
                ->where('owner.id', $userA->id)
                ->where('owner.name', $userA->name)
                ->where('owner.email', $userA->email)
                ->has('members.0', 2)
                ->has('created_at')
                ->has('updated_at')
            )
            ->has('messages.data', 1, fn (AssertableInertia $page) => $page
                ->where('id', $message->id)
                ->where('message', 'Hello World')
                ->where('sender.user_id', $userA->id)
                ->where('sender.name', $userA->name)
                ->has('created_at')
                ->has('updated_at')
            )
            ->where('auth.user.id', $userA->id)
        );
    }
}
