<?php

namespace App\Actions\Product\Category\Subcategory;

use App\Models\Product\ProductCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Find
{
    public function all(ProductCategory $productCategory): Collection
    {
        return $productCategory->subcategories;
    }

    public function limit(ProductCategory $category, int $limit, int $offset = 0): Collection
    {
        return $category

            ->subcategories()
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public function paged(ProductCategory $category, int $perPage, int $page = 1): Collection
    {
        $page = $page > 0 ? $page : 1;

        return $this->limit(
            category: $category,
            limit: $perPage,
            offset: $page * $perPage - $perPage
        );
    }

    public function paginated(ProductCategory $productCategory, int $perPage): LengthAwarePaginator
    {
        return $productCategory

            ->subcategories()
            ->paginate($perPage);
    }
}
