<?php

namespace App\Actions\Product\Category;

use App\Actions\Action;
use App\Models\Product\ProductCategory;
use Illuminate\Support\Collection;

class GetTree extends Action
{
    protected function findParentsTree(ProductCategory $category, Collection $collection = null): Collection
    {
        $collection = $collection ?? collect();

        if ($category->parent) {
            $collection->push($category->parent);

            $this->findParentsTree($category->parent, $collection);
        }

        return $collection->reverse()->values();
    }

    public function handle(ProductCategory $category): Collection
    {
        return $this->findParentsTree($category);
    }
}
