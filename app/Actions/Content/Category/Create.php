<?php

namespace App\Actions\Content\Category;

use App\Actions\Action;
use App\Models\Content\ContentCategory;

class Create extends Action
{
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function handle(array $input): ContentCategory
    {
        return ContentCategory::create($this->validate($input));
    }
}
