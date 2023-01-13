<?php

namespace App\Actions\Product\Specification;

use App\Actions\Action;
use App\Models\Product\ProductSpecification;

class Delete extends Action
{
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function handle(ProductSpecification $productMaster): bool
    {
        return $productMaster->delete();
    }
}
