<?php

namespace App\Actions\Role;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class BulkDelete
{
    public function handle(Collection $roles): bool
    {
        return Role::whereIn('id', $roles->map(fn (Role $role) => $role->getKey()))->delete();
    }
}
