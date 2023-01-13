<?php

namespace App\Actions\Permission;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

class BulkDelete
{
    public function handle(Collection $permissions): bool
    {
        return Permission::whereIn('id', $permissions->map(
            fn (Permission $permission) => $permission->getKey()
        ))
            ->delete();
    }
}
