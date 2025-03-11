<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;

class NotificationService
{
    public function __construct()
    {
    }

    public function markAsRead(int $id):void
    {
        $notification = DatabaseNotification::findOrFail($id);

        $notification->markAsRead();
    }

    public function markAllAsRead(User $user): void
    {
        auth()->user()->unreadNotifications->markAsRead();

    }

    public function delete($id): void
    {
        $notification = DatabaseNotification::findOrFail($id);

        $notification->delete();
    }

    public function getNotifications(User $user): collection
    {
        return $user->unreadNotifications()->get();
    }
}
