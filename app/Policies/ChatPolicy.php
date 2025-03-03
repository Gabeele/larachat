<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {


    return true;}

    public function view(User $user, Chat $chat): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Chat $chat): bool
    {
        return true;
    }

    public function delete(User $user, Chat $chat): bool
    {
        return true;
    }

    public function restore(User $user, Chat $chat): bool
    {
        return true;
    }

    public function forceDelete(User $user, Chat $chat): bool
    {
        return true;
    }
}
