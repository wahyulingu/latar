<?php

namespace App\Actions\Permission;

use App\Actions\Action;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission;

class Create extends Action
{
    public function handle(array $input)
    {
        $data = $this->validate($input, [
            'name' => [
                'required', 'string', 'max:255',

                Rule::unique('permissions')

                    ->where(
                        fn (Builder $query) => $query->where('guard_name', $input['guard_name'] ?? Guard::getDefaultName(Permission::class))
                    ),
            ],

            'guard_name' => ['nullable', 'string', 'max:255'],
        ]);

        return Permission::create($data);
    }
}
