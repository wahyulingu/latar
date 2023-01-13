<?php

namespace App\Actions\Content\Video;

use App\Contracts\Model\HasVideos;
use App\Models\Content\ContentHistory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Find
{
    public function all(HasVideos $content): Collection
    {
        return $content->videos;
    }

    public function byId(HasVideos $content, string $key): ContentHistory
    {
        return $content

            ->videos()
            ->find($key);
    }

    public function show(ContentHistory $contentHistory)
    {
        return $contentHistory->with(['content'])->get();
    }

    public function limit(HasVideos $content, int $limit, int $offset = 0): Collection
    {
        return $content->videos()->limit($limit)

            ->offset($offset)
            ->get();
    }

    public function byPage(HasVideos $content, int $perPage, int $page = 1)
    {
        $page = $page > 0 ? $page : 1;

        return $this

            ->limit(
                content: $content,
                limit: $perPage,
                offset: $page * $perPage - $perPage
            );
    }

    public function paginate(HasVideos $content, int $perPage): LengthAwarePaginator
    {
        return $content

            ->videos()
            ->paginate($perPage);
    }
}
