<?php

namespace App\Actions\Product\Variant;

use App\Models\Product\ProductMaster;
use App\Models\Product\ProductVariant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
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
        return ProductVariant::where(
            fn (Builder $queryBuilder) => $queryBuilder
                ->where('name', 'LIKE', '%'.$query.'%')
                ->orWhere('description', 'LIKE', '%'.$query.'%')
        );
    }

    public function all(ProductMaster $product = null): Collection
    {
        return $product?->variants ?? ProductVariant::all();
    }

    public function byId(ProductMaster $product, string $key): ProductVariant
    {
        return $product

            ->variants()
            ->find($key);
    }

    public function show(ProductVariant $productVariant)
    {
        return $productVariant->with(['product'])->get();
    }

    public function limit(ProductMaster $product, int $limit, int $offset = 0): Collection
    {
        return $product

            ->variants()
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public function byPage(ProductMaster $product, int $perPage, int $page = 1)
    {
        $page = $page > 0 ? $page : 1;

        return $this

            ->limit(
                $product,
                limit: $perPage,
                offset: $page * $perPage - $perPage
            );
    }

    public function paginate(ProductMaster $product, int $perPage): LengthAwarePaginator
    {
        return $product

            ->variants()
            ->paginate($perPage);
    }
}
