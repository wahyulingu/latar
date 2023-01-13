<?php

namespace App\Actions\Profile\Driver;

use App\Models\User;

class Check
{
    public function hasProfile(User $user): bool
    {
        return (bool) $user->driverProfile;
    }
}
