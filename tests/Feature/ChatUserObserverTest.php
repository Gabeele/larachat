<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\User;
use App\Notifications\AddedToGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ChatUserObserverTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_sends_notification_when_user_is_added_to_chat()
    {
        // Prevent actual notifications from being sent
        Notification::fake();

        // Create a chat and users
        $chat = Chat::factory()->create();
        $existingUser = User::factory()->create();
        $newUser = User::factory()->create();

        ChatUser::withoutEvents(function() use ($chat, $existingUser) {
            $chat->users()->attach($existingUser);
        });

        ChatUser::factory()->create([
            'chat_id' => $chat->id,
            'user_id' => $newUser->id,
        ]);

        Notification::assertSentTo(
            $newUser,
            AddedToGroup::class,
            function ($notification, $channels) use ($chat, $newUser) {
                return $notification->chat->id === $chat->id &&
                    $notification->user->id === $newUser->id;
            }
        );
    }

    public function test_it_send_notification_when_chat_is_created()
    {
        Notification::fake();

        $user = User::factory()->create();

        $member1 = User::factory()->create();
        $member2 = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('chat.store'), [
            'member_ids' => [$member1->id, $member2->id],
            'name' => 'Test Chat Group',
        ]);

        $response->assertRedirect();

        $chat = Chat::latest()->first();

        $this->assertNotNull($chat);
        $this->assertEquals('Test Chat Group', $chat->name);

        Notification::assertSentTo(
            $member1,
            AddedToGroup::class,
            function ($notification, $channels) use ($chat, $member1) {
                return $notification->chat->id === $chat->id &&
                    $notification->user->id === $member1->id;
            }
        );

        Notification::assertSentTo(
            $member2,
            AddedToGroup::class,
            function ($notification, $channels) use ($chat, $member2) {
                return $notification->chat->id === $chat->id &&
                    $notification->user->id === $member2->id;
            }
        );
    }
}
