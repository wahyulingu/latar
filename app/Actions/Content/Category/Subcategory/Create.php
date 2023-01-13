<?php

namespace App\Actions\Content\Category\Subcategory;

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

    public function handle(ContentCategory $parentCategory, array $input): ContentCategory
    {
        return $parentCategory->subcategories()->create($this->validate($input));
    }
}
