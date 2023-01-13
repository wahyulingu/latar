<?php

namespace App\Actions\Profile\Customer;

use App\Models\User;

class Check
{
    public function hasProfile(User $user): bool
    {
        return (bool) $user->customerProfile;
    }
}
