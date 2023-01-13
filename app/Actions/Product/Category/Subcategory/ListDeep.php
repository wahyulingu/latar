<?php

namespace App\Actions\Product\Category\Subcategory;

use App\Actions\Action;
use App\Models\Product\ProductCategory;
use Illuminate\Support\Collection;

class ListDeep extends Action
{
    protected function findDeepSubcategories(ProductCategory $category, Collection $collection = null): Collection
    {
        $collection = $collection ?? collect();

        $category->subcategories->each(
            fn (ProductCategory $subcategory) => $collection->push($subcategory) && $this->findDeepSubcategories($subcategory, $collection)
        );

        return $collection;
    }

    public function handle(ProductCategory $category): Collection
    {
        return $this->findDeepSubcategories($category);
    }
}
