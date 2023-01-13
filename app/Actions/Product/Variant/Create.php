<?php

namespace App\Actions\Product\Variant;

use App\Actions\Action;
use App\Models\Product\ProductMaster;
use App\Models\Product\ProductVariant;

class Create extends Action
{
    protected function rules(): array
    {
        return [
            'price' => ['required', 'numeric'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }

    public function handle(ProductMaster $productMaster, array $input): ProductVariant
    {
        $data = $this->validate($input);

        return $productMaster

            ->variants()
            ->create($data);
    }
}
