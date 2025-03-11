<?php
namespace App\Http\Middleware;

use App\Http\Resources\ChatResource;
use App\Services\ChatService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Inertia\Middleware;

class SharedDataMiddleware extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $chatService = app(ChatService::class);
        $notificationService = app(NotificationService::class);
        $chats = $chatService->getChats(auth()->user());
        $notifications = $notificationService->getNotifications(auth()->user());

        return array_merge(parent::share($request), [
            'chats' => ChatResource::collection($chats),
            'notifications' => $notifications->toArray(),
        ]);
    }
}
