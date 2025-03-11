<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function markAsRead($id, NotificationService $notificationService)
    {
        $notificationService->markAsRead($id);

        return redirect()->back();
    }

    public function markAllAsRead(NotificationService $notificationService)
    {
        $notificationService->markAllAsRead(auth()->user());

        return redirect()->back();
    }

    // Delete a notification
    public function destroy($id, NotificationService $notificationService)
    {
        $notificationService->delete($id);
        return redirect()->back();
    }

}
