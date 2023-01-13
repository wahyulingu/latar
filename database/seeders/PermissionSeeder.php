<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    protected array $entities = [
        'permission',
        'role',
        'user',
    ];

    protected array $abilities = [
        'create',
        'view',
        'update',
        'delete',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::firstOrCreate(['name' => 'access.dashboard']);

        foreach ($this->entities as $entity) {
            foreach ($this->abilities as $ability) {
                Permission::firstOrCreate(['name' => implode('.', [$ability, $entity])]);
            }
        }
    }
}
