<?php

namespace App\Actions\Product\Specification;

use App\Actions\Action;
use App\Contracts\Model\HasSpecifications;
use App\Models\Product\ProductSpecification;

class Add extends Action
{
    protected function rules(): array
    {
        return [
            'icon' => ['nullable', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function handle(HasSpecifications $specificationable, array $input): ProductSpecification
    {
        $data = $this->validate($input);

        return $specificationable

            ->specifications()
            ->create($data);
    }
}
