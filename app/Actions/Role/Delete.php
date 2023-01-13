<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;

class Delete
{
    public function handle(Role $role): bool
    {
        return $role->delete();
    }
}
