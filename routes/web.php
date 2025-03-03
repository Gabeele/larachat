<?php

use App\Http\Controllers\DashboardController;
use App\Http\Middleware\SharedDataMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', DashboardController::class)->middleware(['auth', 'verified', SharedDataMiddleware::class])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
