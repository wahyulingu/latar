<?php

namespace App\Actions\Content\Article;

use App\Models\Content\ContentArticle;
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
        return ContentArticle::where(
            fn (Builder $articleQueryBuilder) => $articleQueryBuilder
                ->where('title', 'LIKE', '%'.$query.'%')
                ->orWhere('description', 'LIKE', '%'.$query.'%')

                ->orWhereHas('category',
                    fn (Builder $categoryQueryBuilder) => $categoryQueryBuilder
                        ->where('title', 'LIKE', '%'.$query.'%')
                        ->orWhere('description', 'LIKE', '%'.$query.'%')));
    }

    public function all(): Collection
    {
        return ContentArticle::all();
    }

    public function byId(string $key): ContentArticle
    {
        return ContentArticle::find($key);
    }

    public function show(ContentArticle $articleArticle)
    {
        return $articleArticle->with(['histories'])->get();
    }

    public function limit(int $limit, int $offset = 0): Collection
    {
        return ContentArticle::limit($limit)

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
        return ContentArticle::paginate($perPage);
    }

    public function onlyRootAll(): Collection
    {
        return ContentArticle::doesntHave('articleArticle')->get();
    }

    public function onlyRootLimit(int $limit, int $offset = 0): Collection
    {
        return ContentArticle::doesntHave('articleArticle')->limit($limit)

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
        return ContentArticle::doesntHave('articleArticle')->paginate($perPage);
    }
}
