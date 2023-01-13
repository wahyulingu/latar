<?php

namespace App\Actions;

use Illuminate\Support\Facades\Validator;

abstract class Action
{
    protected function rules(): array
    {
        return [];
    }

    final protected function validate(array $input, array $rules = null): array
    {
        return Validator::make($input, $rules ?? $this->rules())->validate();
    }
}
