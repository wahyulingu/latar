<?php

namespace App\Actions\Content\History;

use App\Models\Content\ContentHistory;
use App\Models\Content\ContentMaster;
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
        return ContentHistory::where(
            fn (Builder $queryBuilder) => $queryBuilder
                ->where('name', 'LIKE', '%'.$query.'%')
                ->orWhere('description', 'LIKE', '%'.$query.'%')
        );
    }

    public function all(ContentMaster $article = null): Collection
    {
        return $article?->histories ?? ContentHistory::all();
    }

    public function byId(ContentMaster $article, string $key): ContentHistory
    {
        return $article

            ->histories()
            ->find($key);
    }

    public function show(ContentHistory $articleHistory)
    {
        return $articleHistory->with(['article'])->get();
    }

    public function limit(ContentMaster $article, int $limit, int $offset = 0): Collection
    {
        return $article

            ->histories()
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public function byPage(ContentMaster $article, int $perPage, int $page = 1)
    {
        $page = $page > 0 ? $page : 1;

        return $this

            ->limit(
                $article,
                limit: $perPage,
                offset: $page * $perPage - $perPage
            );
    }

    public function paginate(ContentMaster $article, int $perPage): LengthAwarePaginator
    {
        return $article

            ->histories()
            ->paginate($perPage);
    }
}
