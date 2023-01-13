<?php

namespace App\Traits\Model\Product;

use App\Models\Product\ProductSpecification;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasSpecifications
{
    public function specifications(): MorphMany
    {
        return $this->morpMany(ProductSpecification::class, 'product');
    }
}
