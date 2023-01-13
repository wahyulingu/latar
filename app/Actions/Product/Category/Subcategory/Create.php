<?php

namespace App\Actions\Product\Category\Subcategory;

use App\Actions\Action;
use App\Models\Product\ProductCategory;

class Create extends Action
{
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function handle(ProductCategory $parentCategory, array $input): ProductCategory
    {
        return $parentCategory->subcategories()->create($this->validate($input));
    }
}
