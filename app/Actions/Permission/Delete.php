<?php

namespace App\Actions\Permission;

use Spatie\Permission\Models\Permission;

class Delete
{
    public function handle(Permission $permission): bool
    {
        return $permission->delete();
    }
}
