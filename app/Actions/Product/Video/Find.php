<?php

namespace App\Actions\Product\Video;

use App\Contracts\Model\HasVideos;
use App\Models\Product\ProductVariant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Find
{
    public function all(HasVideos $product): Collection
    {
        return $product->videos;
    }

    public function byId(HasVideos $product, string $key): ProductVariant
    {
        return $product

            ->videos()
            ->find($key);
    }

    public function show(ProductVariant $productVariant)
    {
        return $productVariant->with(['product'])->get();
    }

    public function limit(HasVideos $product, int $limit, int $offset = 0): Collection
    {
        return $product->videos()->limit($limit)

            ->offset($offset)
            ->get();
    }

    public function byPage(HasVideos $product, int $perPage, int $page = 1)
    {
        $page = $page > 0 ? $page : 1;

        return $this

            ->limit(
                product: $product,
                limit: $perPage,
                offset: $page * $perPage - $perPage
            );
    }

    public function paginate(HasVideos $product, int $perPage): LengthAwarePaginator
    {
        return $product

            ->videos()
            ->paginate($perPage);
    }
}
