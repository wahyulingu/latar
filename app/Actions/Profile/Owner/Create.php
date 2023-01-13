<?php

namespace App\Actions\Profile\Owner;

use App\Actions\Action;
use App\Exceptions\Dashboard\Profile\ProfileExistsException;
use App\Models\Profile\ProfileOwner;
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:profile_owners'],
            'phone' => ['required', 'string', new PhoneNumberRule(), 'unique:profile_owners'],
            'brand' => ['required', 'string', 'max:255', 'unique:profile_owners'],
            'bio' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
        ];
    }

    public function handle(User $user, array $input): ProfileOwner
    {
        if (!$this->check->hasProfile($user)) {
            return $user->ownerProfile()->create($this->validate($input));
        }

        throw new ProfileExistsException();
    }
}
