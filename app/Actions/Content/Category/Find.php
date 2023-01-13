<?php

namespace App\Actions\Content\Category;

use App\Models\Content\ContentCategory;
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
        return ContentCategory::where(
            fn (Builder $queryBuilder) => $queryBuilder
                ->where('name', 'LIKE', '%'.$query.'%')
                ->orWhere('description', 'LIKE', '%'.$query.'%')
        );
    }

    public function all(): Collection
    {
        return ContentCategory::all();
    }

    public function byId(string $key): ContentCategory
    {
        return ContentCategory::find($key);
    }

    public function show(ContentCategory $articleCategory)
    {
        return $articleCategory->with(['subcategories', 'articles'])->get();
    }

    public function limit(int $limit, int $offset = 0): Collection
    {
        return ContentCategory::limit($limit)

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
        return ContentCategory::paginate($perPage);
    }

    public function onlyRootAll(): Collection
    {
        return ContentCategory::doesntHave('subcategories')

            ->get();
    }

    public function onlyRootLimit(int $limit, int $offset = 0): Collection
    {
        return ContentCategory::doesntHave('subcategories')->limit($limit)

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
        return ContentCategory::doesntHave('subcategories')->paginate($perPage);
    }
}
