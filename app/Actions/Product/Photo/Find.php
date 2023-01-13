<?php

namespace App\Actions\Product\Photo;

use App\Contracts\Model\HasPhotos;
use App\Models\Product\ProductVariant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Find
{
    public function all(HasPhotos $product): Collection
    {
        return $product->photos;
    }

    public function byId(HasPhotos $product, string $key): ProductVariant
    {
        return $product

            ->photos()
            ->find($key);
    }

    public function show(ProductVariant $productVariant)
    {
        return $productVariant->with(['product'])->get();
    }

    public function limit(HasPhotos $product, int $limit, int $offset = 0): Collection
    {
        return $product->photos()->limit($limit)

            ->offset($offset)
            ->get();
    }

    public function byPage(HasPhotos $product, int $perPage, int $page = 1)
    {
        $page = $page > 0 ? $page : 1;

        return $this

            ->limit(
                product: $product,
                limit: $perPage,
                offset: $page * $perPage - $perPage
            );
    }

    public function paginate(HasPhotos $product, int $perPage): LengthAwarePaginator
    {
        return $product

            ->photos()
            ->paginate($perPage);
    }
}
