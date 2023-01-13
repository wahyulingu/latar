<?php

namespace App\Actions\Profile\Owner;

use App\Actions\Action;
use App\Models\Profile\ProfileOwner;
use App\Rules\PhoneNumberRule;

class Update extends Action
{
    public function __construct(protected Check $check)
    {
    }

    protected function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:profile_owners'],
            'phone' => ['nullable', 'string', new PhoneNumberRule(), 'unique:profile_owners'],
            'brand' => ['nullable', 'string', 'max:255', 'unique:profile_owners'],
            'bio' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function handle(ProfileOwner $profile, array $input): bool
    {
        $data = [];

        foreach ($this->validate($input) as $key => $value) {
            if (!empty($value)) {
                $data[$key] = $value;
            }
        }

        return $profile->update($data);
    }
}
