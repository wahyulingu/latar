<?php

namespace App\Actions\Content\Category\Subcategory;

use App\Actions\Action;
use App\Models\Content\ContentCategory;
use Illuminate\Support\Collection;

class ListDeep extends Action
{
    protected function findDeepSubcategories(ContentCategory $category, Collection $collection = null): Collection
    {
        $collection = $collection ?? collect();

        $category->subcategories->each(
            fn (ContentCategory $subcategory) => $collection->push($subcategory) && $this->findDeepSubcategories($subcategory, $collection)
        );

        return $collection;
    }

    public function handle(ContentCategory $category): Collection
    {
        return $this->findDeepSubcategories($category);
    }
}
