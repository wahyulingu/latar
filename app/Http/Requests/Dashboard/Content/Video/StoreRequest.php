<?php

namespace App\Http\Requests\Dashboard\Content\Video;

use App\Models\Content\ContentArticle;
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
        return $this->user()->can('create', ContentArticle::class);
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
            'video' => ['required', 'file', 'mimetypes:video/mpeg', 'max:20480'],
        ];
    }
}
