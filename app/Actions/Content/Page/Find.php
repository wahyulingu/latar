<?php

namespace App\Actions\Content\Page;

use App\Models\Content\ContentPage;
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
        return ContentPage::where(
            fn (Builder $pageQueryBuilder) => $pageQueryBuilder
                ->where('title', 'LIKE', '%'.$query.'%')
                ->orWhere('description', 'LIKE', '%'.$query.'%')

                ->orWhereHas('category',
                    fn (Builder $categoryQueryBuilder) => $categoryQueryBuilder
                        ->where('title', 'LIKE', '%'.$query.'%')
                        ->orWhere('description', 'LIKE', '%'.$query.'%')));
    }

    public function all(): Collection
    {
        return ContentPage::all();
    }

    public function byId(string $key): ContentPage
    {
        return ContentPage::find($key);
    }

    public function show(ContentPage $pagePage)
    {
        return $pagePage->with(['histories'])->get();
    }

    public function limit(int $limit, int $offset = 0): Collection
    {
        return ContentPage::limit($limit)

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
        return ContentPage::paginate($perPage);
    }

    public function onlyRootAll(): Collection
    {
        return ContentPage::doesntHave('pagePage')->get();
    }

    public function onlyRootLimit(int $limit, int $offset = 0): Collection
    {
        return ContentPage::doesntHave('pagePage')->limit($limit)

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
        return ContentPage::doesntHave('pagePage')->paginate($perPage);
    }
}
