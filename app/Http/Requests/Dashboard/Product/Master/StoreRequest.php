<?php

namespace App\Http\Requests\Dashboard\Product\Master;

use App\Models\Product\ProductCategory;
use App\Models\Product\ProductMaster;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', ProductMaster::class);
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
            'description' => ['required', 'string', 'max:255'],

            'category_id' => [
                'required',
                sprintf('exists:%s,id', ProductCategory::class),
            ],
        ];
    }
}
