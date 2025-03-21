<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Middleware\SharedDataMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified', SharedDataMiddleware::class])->group(function () {

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('chats/create', [ChatController::class, 'create'])->name('chat.create');
    Route::post('chats', [ChatController::class, 'store'])->name('chat.store');
    Route::get('chats/{chat}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('chats/{chat}/messages', [MessageController::class, 'store'])->name('message.store');

    Route::put('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
