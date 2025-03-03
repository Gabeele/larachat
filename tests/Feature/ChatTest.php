<?php

namespace Tests\Feature;

use App\Models\Chat;
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
}
