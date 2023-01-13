<?php

namespace App\Actions\Content\History;

use App\Actions\Content\Photo\BulkDelete as PhotoBulkDelete;
use App\Actions\Content\Video\BulkDelete as VideoBulkDelete;
use App\Models\Content\ContentHistory;
use App\Models\Content\ContentPhoto;
use App\Models\Content\ContentVideo;
use Illuminate\Support\Collection;

class BulkDelete
{
    public function __construct(
        protected PhotoBulkDelete $photoBulkDelete,
        protected VideoBulkDelete $videoBulkDelete
    ) {
    }

    public function handle(Collection $histories): bool|null
    {
        $photosCollection = collect();
        $videosCollection = collect();

        $histories->each(
            function (ContentHistory $history) use ($photosCollection, $videosCollection) {
                $history->photos->each(fn (ContentPhoto $photo) => $photosCollection->push($photo));
                $history->videos->each(fn (ContentVideo $video) => $videosCollection->push($video));
            }
        );

        $this->photoBulkDelete->handle($photosCollection);
        $this->videoBulkDelete->handle($videosCollection);

        return ContentHistory::whereIn('id', $histories->map(fn (ContentHistory $history) => $history->getKey()))->delete();
    }
}
