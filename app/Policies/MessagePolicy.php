<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    return true;
    }

    public function view(User $user, Message $message): bool
    {
    return true;
    }

    public function create(User $user): bool
    {
    return true;
    }

    public function update(User $user, Message $message): bool
    {
    return true;
    }

    public function delete(User $user, Message $message): bool
    {
    return true;
    }

    public function restore(User $user, Message $message): bool
    {
    return true;
    }

    public function forceDelete(User $user, Message $message): bool
    {
    return true;
    }
}
