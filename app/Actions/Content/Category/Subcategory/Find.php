<?php

namespace App\Actions\Content\Category\Subcategory;

use App\Models\Content\ContentCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Find
{
    public function all(ContentCategory $articleCategory): Collection
    {
        return $articleCategory->subcategories;
    }

    public function limit(ContentCategory $category, int $limit, int $offset = 0): Collection
    {
        return $category

            ->subcategories()
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public function paged(ContentCategory $category, int $perPage, int $page = 1): Collection
    {
        $page = $page > 0 ? $page : 1;

        return $this->limit(
            category: $category,
            limit: $perPage,
            offset: $page * $perPage - $perPage
        );
    }

    public function paginated(ContentCategory $articleCategory, int $perPage): LengthAwarePaginator
    {
        return $articleCategory

            ->subcategories()
            ->paginate($perPage);
    }
}
