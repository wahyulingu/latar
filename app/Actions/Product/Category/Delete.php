<?php

namespace App\Actions\Product\Category;

use App\Actions\Action;
use App\Actions\Product\Category\Subcategory\ListDeep;
use App\Models\Product\ProductCategory;

class Delete extends Action
{
    public function __construct(protected ListDeep $listDeepOfSubcategories)
    {
    }

    protected function deleteAllSubcategories(ProductCategory $productCategory)
    {
        $subcategories = $this->listDeepOfSubcategories->handle($productCategory);

        $query = ProductCategory::whereIn('id', $subcategories->map(
            fn (ProductCategory $subcategory) => $subcategory->getKey()
        ));

        return $query->delete();
    }

    public function handle(ProductCategory $productCategory): ?bool
    {
        $this->deleteAllSubcategories($productCategory);

        return $productCategory->delete();
    }
}
