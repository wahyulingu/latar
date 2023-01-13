<?php

namespace App\Actions\Profile\Author;

use App\Models\User;

class Check
{
    public function hasProfile(User $user): bool
    {
        return (bool) $user->authorProfile;
    }
}
