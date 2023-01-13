<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;

class Listing
{
    public function handle(int $limit = null, int $offset = null)
    {
        if ($limit && $offset) {
            return Role::limit($limit)->offset($offset)->get();
        }

        if ($limit) {
            return Role::limit($limit)->get();
        }

        return Role::all();
    }
}
