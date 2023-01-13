<?php

namespace App\Http\Requests\Dashboard\Content\Page;

use App\Models\Content\ContentCategory;
use App\Models\Content\ContentPage;
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
        return $this->user()->can('create', ContentPage::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'description' => ['required', 'string', 'max:255'],

            'category_id' => [
                'required',
                sprintf('exists:%s,id', ContentCategory::class),
            ],
        ];
    }
}
