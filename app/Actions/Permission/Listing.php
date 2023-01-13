<?php

namespace App\Actions\Permission;

use Spatie\Permission\Models\Permission;

class Listing
{
    public function handle(int $limit = null, int $offset = null)
    {
        if ($limit && $offset) {
            return Permission::limit($limit)->offset($offset)->get();
        }

        if ($limit) {
            return Permission::limit($limit)->get();
        }

        return Permission::all();
    }
}
