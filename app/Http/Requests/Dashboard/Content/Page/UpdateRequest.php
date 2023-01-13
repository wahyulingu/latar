<?php

namespace App\Http\Requests\Dashboard\Content\Page;

use App\Models\Content\ContentCategory;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('page'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'description' => ['nullable', 'string', 'max:255'],
            'category_id' => [
                'nullable',
                sprintf('exists:%s,id', ContentCategory::class),
            ],
        ];
    }
}
