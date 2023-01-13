<?php

namespace App\Http\Requests\Dashboard\Profile\Customer;

use App\Rules\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is customerized to make this request.
     *
     * @return bool
     */
    public function customerize()
    {
        return $this->user()->can('update', $this->route('user'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:profile_customers'],
            'phone' => ['required', 'string', new PhoneNumberRule(), 'unique:profile_customers'],
            'bio' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
        ];
    }
}
