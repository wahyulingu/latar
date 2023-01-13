<?php

namespace App\Actions\Product\Master;

use App\Actions\Action;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductMaster;
use App\Models\Profile\ProfileOwner;

class Create extends Action
{
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],

            'category_id' => [
                'required',
                sprintf('exists:%s,id', ProductCategory::class),
            ],
        ];
    }

    public function handle(ProfileOwner $owner, array $input): ProductMaster
    {
        $data = $this->validate($input);

        return $owner

            ->products()
            ->create($data);
    }
}
