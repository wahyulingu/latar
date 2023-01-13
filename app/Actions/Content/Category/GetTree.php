<?php

namespace App\Actions\Content\Category;

use App\Actions\Action;
use App\Models\Content\ContentCategory;
use Illuminate\Support\Collection;

class GetTree extends Action
{
    protected function findParentsTree(ContentCategory $category, Collection $collection = null): Collection
    {
        $collection = $collection ?? collect();

        if ($category->parent) {
            $collection->push($category->parent);

            $this->findParentsTree($category->parent, $collection);
        }

        return $collection->reverse()->values();
    }

    public function handle(ContentCategory $category): Collection
    {
        return $this->findParentsTree($category);
    }
}
