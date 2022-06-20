<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isGranted(User::ROLE_SUPERADMIN)) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->isGranted(User::ROLE_PROTECTOR);
    }

    public function view(User $user)
    {
        return $user->isGranted(User::ROLE_PROTECTOR);
    }

    public function delete(User $user)
    {
        return $user->isGranted(User::ROLE_ADMIN);
    }
}
