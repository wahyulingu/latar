<?php

namespace App\Actions\Profile\Customer;

use App\Actions\Action;
use App\Exceptions\Dashboard\Profile\ProfileExistsException;
use App\Models\Profile\ProfileCustomer;
use App\Models\User;
use App\Rules\PhoneNumberRule;

class Create extends Action
{
    public function __construct(protected Check $check)
    {
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:profile_customers'],
            'phone' => ['required', 'string', new PhoneNumberRule(), 'unique:profile_customers'],
            'bio' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
        ];
    }

    public function handle(User $user, array $input): ProfileCustomer
    {
        if (!$this->check->hasProfile($user)) {
            return $user->customerProfile()->create($this->validate($input));
        }

        throw new ProfileExistsException();
    }
}
