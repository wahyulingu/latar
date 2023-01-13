<?php

namespace App\Actions\Profile\Owner;

use App\Models\User;

class Check
{
    public function hasProfile(User $user): bool
    {
        return (bool) $user->ownerProfile;
    }
}
