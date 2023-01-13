<?php

namespace App\Actions\Product\Specification;

use App\Contracts\Model\HasSpecifications;
use App\Models\Product\ProductVariant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Find
{
    public function all(HasSpecifications $product): Collection
    {
        return $product->specifications;
    }

    public function byId(HasSpecifications $product, string $key): ProductVariant
    {
        return $product

            ->specifications()
            ->find($key);
    }

    public function show(ProductVariant $productVariant)
    {
        return $productVariant->with(['product'])->get();
    }

    public function limit(HasSpecifications $product, int $limit, int $offset = 0): Collection
    {
        return $product->specifications()->limit($limit)

            ->offset($offset)
            ->get();
    }

    public function byPage(HasSpecifications $product, int $perPage, int $page = 1)
    {
        $page = $page > 0 ? $page : 1;

        return $this

            ->limit(
                product: $product,
                limit: $perPage,
                offset: $page * $perPage - $perPage
            );
    }

    public function paginate(HasSpecifications $product, int $perPage): LengthAwarePaginator
    {
        return $product

            ->specifications()
            ->paginate($perPage);
    }
}
