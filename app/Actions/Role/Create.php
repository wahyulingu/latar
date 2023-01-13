<?php

namespace App\Actions\Role;

use App\Actions\Action;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Role;

class Create extends Action
{
    public function handle(array $input)
    {
        $data = $this->validate($input, [
            'name' => [
                'required', 'string', 'max:255',

                Rule::unique('roles')

                    ->where(
                        fn (Builder $query) => $query->where('guard_name', $input['guard_name'] ?? Guard::getDefaultName(Role::class))
                    ),
            ],

            'guard_name' => ['nullable', 'string', 'max:255'],
        ]);

        return Role::create($data);
    }
}
