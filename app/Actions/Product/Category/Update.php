<?php

namespace App\Actions\Product\Category;

use App\Actions\Action;
use App\Models\Product\ProductCategory;

class Update extends Action
{
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }

    public function handle(ProductCategory $productCategory, array $input): bool
    {
        return $productCategory->update($this->validate($input));
    }
}
