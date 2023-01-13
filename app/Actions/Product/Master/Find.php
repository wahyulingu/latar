<?php

namespace App\Actions\Product\Master;

use App\Models\Product\ProductMaster;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Find
{
    public function handle(?string $query = null, int $paginate = 8): LengthAwarePaginator
    {
        $handler = empty($query)

            ? $this
            : $this->query($query);

        return $handler->paginate($paginate);
    }

    public function query(string $query): Builder
    {
        return ProductMaster::where(
            fn (Builder $productQueryBuilder) => $productQueryBuilder
                ->where('name', 'LIKE', '%'.$query.'%')
                ->orWhere('description', 'LIKE', '%'.$query.'%')

                ->orWhereHas('category',
                    fn (Builder $categoryQueryBuilder) => $categoryQueryBuilder
                        ->where('name', 'LIKE', '%'.$query.'%')
                        ->orWhere('description', 'LIKE', '%'.$query.'%')));
    }

    public function all(): Collection
    {
        return ProductMaster::all();
    }

    public function byId(string $key): ProductMaster
    {
        return ProductMaster::find($key);
    }

    public function show(ProductMaster $productMaster)
    {
        return $productMaster->with(['variants'])->get();
    }

    public function limit(int $limit, int $offset = 0): Collection
    {
        return ProductMaster::limit($limit)

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
        return ProductMaster::paginate($perPage);
    }

    public function onlyRootAll(): Collection
    {
        return ProductMaster::doesntHave('productMaster')->get();
    }

    public function onlyRootLimit(int $limit, int $offset = 0): Collection
    {
        return ProductMaster::doesntHave('productMaster')->limit($limit)

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
        return ProductMaster::doesntHave('productMaster')->paginate($perPage);
    }
}
