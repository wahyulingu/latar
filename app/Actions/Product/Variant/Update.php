<?php

namespace App\Actions\Product\Variant;

use App\Actions\Action;
use App\Models\Product\ProductMaster;
use App\Models\Product\ProductVariant;

class Update extends Action
{
    protected function rules(): array
    {
        return [
            'price' => ['nullable', 'numeric'],
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],

            'master_id' => [
                'nullable',
                sprintf('exists:%s,id', ProductMaster::class),
            ],
        ];
    }

    public function handle(ProductVariant $model, array $input): bool
    {
        $this->validate($input);

        if (!empty($input['price'])) {
            $model->price = $input['price'];
        }

        if (!empty($input['name'])) {
            $model->name = $input['name'];
        }

        if (!empty($input['description'])) {
            $model->description = $input['description'];
        }

        if (!empty($input['master_id'])) {
            $model->master_id = $input['master_id'];
        }

        return $model->save();
    }
}
