<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Services\ChatService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Dashboard', []);
    }
}
