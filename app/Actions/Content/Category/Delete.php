<?php

namespace App\Actions\Content\Category;

use App\Actions\Action;
use App\Actions\Content\Category\Subcategory\ListDeep;
use App\Models\Content\ContentCategory;

class Delete extends Action
{
    public function __construct(protected ListDeep $listDeepOfSubcategories)
    {
    }

    protected function deleteAllSubcategories(ContentCategory $articleCategory)
    {
        $subcategories = $this->listDeepOfSubcategories->handle($articleCategory);

        $query = ContentCategory::whereIn('id', $subcategories->map(
            fn (ContentCategory $subcategory) => $subcategory->getKey()
        ));

        return $query->delete();
    }

    public function handle(ContentCategory $articleCategory): ?bool
    {
        $this->deleteAllSubcategories($articleCategory);

        return $articleCategory->delete();
    }
}
