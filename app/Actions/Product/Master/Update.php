<?php

namespace App\Actions\Product\Master;

use App\Actions\Action;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductMaster;

class Update extends Action
{
    protected function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],

            'category_id' => [
                'nullable',
                sprintf('exists:%s,id', ProductCategory::class),
            ],
        ];
    }

    public function handle(ProductMaster $model, array $input): bool
    {
        $data = $this->validate($input);

        if (!empty($data['name'])) {
            $model->name = $data['name'];
        }
        if (!empty($data['description'])) {
            $model->description = $data['description'];
        }
        if (!empty($data['category_id'])) {
            $model->master_id = $data['master_id'];
        }

        return $model->save();
    }
}
