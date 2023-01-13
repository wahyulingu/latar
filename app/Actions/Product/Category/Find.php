<?php

namespace App\Actions\Product\Category;

use App\Models\Product\ProductCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Find
{
    public function handle(string $query = null, int $paginate = 8): LengthAwarePaginator
    {
        $handler = empty($query)

            ? $this
            : $this->query($query);

        return $handler->paginate($paginate);
    }

    public function query(string $query): Builder
    {
        return ProductCategory::where(
            fn (Builder $queryBuilder) => $queryBuilder
                ->where('name', 'LIKE', '%'.$query.'%')
                ->orWhere('description', 'LIKE', '%'.$query.'%')
        );
    }

    public function all(): Collection
    {
        return ProductCategory::all();
    }

    public function byId(string $key): ProductCategory
    {
        return ProductCategory::find($key);
    }

    public function show(ProductCategory $productCategory)
    {
        return $productCategory->with(['subcategories', 'products'])->get();
    }

    public function limit(int $limit, int $offset = 0): Collection
    {
        return ProductCategory::limit($limit)

            ->offset($offset)
            ->get();
    }

    public function byPage(int $perPage, int $page = 1)
    {
        $page = $page > 0 ? $page : 1;

        return $this->limit(
            limit: $perPage,
            offset: $page * $perPage - $perPage
        );
    }

    public function paginate(int $perPage): LengthAwarePaginator
    {
        return ProductCategory::paginate($perPage);
    }

    public function onlyRootAll(): Collection
    {
        return ProductCategory::doesntHave('subcategories')

            ->get();
    }

    public function onlyRootLimit(int $limit, int $offset = 0): Collection
    {
        return ProductCategory::doesntHave('subcategories')->limit($limit)

            ->offset($offset)
            ->get();
    }

    public function onlyRootByPage(int $perPage, int $page = 1)
    {
        $page = $page > 0 ? $page : 1;

        return $this->onlyRootLimit(
            limit: $perPage,
            offset: $page * $perPage - $perPage
        );
    }

    public function onlyRootPaginate(int $perPage): LengthAwarePaginator
    {
        return ProductCategory::doesntHave('subcategories')->paginate($perPage);
    }
}
