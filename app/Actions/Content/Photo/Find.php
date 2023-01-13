<?php

namespace App\Actions\Content\Photo;

use App\Contracts\Model\HasPhotos;
use App\Models\Content\ContentHistory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Find
{
    public function all(HasPhotos $content): Collection
    {
        return $content->photos;
    }

    public function byId(HasPhotos $content, string $key): ContentHistory
    {
        return $content

            ->photos()
            ->find($key);
    }

    public function show(ContentHistory $contentHistory)
    {
        return $contentHistory->with(['content'])->get();
    }

    public function limit(HasPhotos $content, int $limit, int $offset = 0): Collection
    {
        return $content->photos()->limit($limit)

            ->offset($offset)
            ->get();
    }

    public function byPage(HasPhotos $content, int $perPage, int $page = 1)
    {
        $page = $page > 0 ? $page : 1;

        return $this

            ->limit(
                content: $content,
                limit: $perPage,
                offset: $page * $perPage - $perPage
            );
    }

    public function paginate(HasPhotos $content, int $perPage): LengthAwarePaginator
    {
        return $content

            ->photos()
            ->paginate($perPage);
    }
}
