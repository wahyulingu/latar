<?php

namespace App\Actions\Profile\Customer;

use App\Actions\Action;
use App\Models\Profile\ProfileCustomer;
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
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:profile_customers'],
            'phone' => ['nullable', 'string', new PhoneNumberRule(), 'unique:profile_customers'],
            'bio' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function handle(ProfileCustomer $profile, array $input): bool
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
