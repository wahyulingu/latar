<?php

namespace App\Actions\Profile\Driver;

use App\Actions\Action;
use App\Exceptions\Dashboard\Profile\ProfileExistsException;
use App\Models\Profile\ProfileDriver;
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:profile_drivers'],
            'phone' => ['required', 'string', new PhoneNumberRule(), 'unique:profile_drivers'],
            'bio' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
        ];
    }

    public function handle(User $user, array $input): ProfileDriver
    {
        if (!$this->check->hasProfile($user)) {
            return $user->driverProfile()->create($this->validate($input));
        }

        throw new ProfileExistsException();
    }
}
