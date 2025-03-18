<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, $id, NotificationService $notificationService)
    {
        $notificationService->markAsRead($id);

        if($request->expectsJson()){
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    public function markAllAsRead(Request $request, NotificationService $notificationService)
    {
        $notificationService->markAllAsRead(auth()->user());

        if($request->expectsJson()){
            return response()->json(['success' => true]);
        }
        return redirect()->back();
    }

    public function destroy(Request $request, $id, NotificationService $notificationService)
    {
        $notificationService->delete($id);

        if($request->expectsJson()){
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

}
