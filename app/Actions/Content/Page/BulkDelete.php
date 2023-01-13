<?php

namespace App\Actions\Content\Page;

use App\Actions\Content\History\BulkDelete as HistoryBulkDelete;
use App\Actions\Content\Photo\BulkDelete as PhotoBulkDelete;
use App\Actions\Content\Video\BulkDelete as VideoBulkDelete;
use App\Models\Content\ContentHistory;
use App\Models\Content\ContentPage;
use App\Models\Content\ContentPhoto;
use App\Models\Content\ContentVideo;
use Illuminate\Support\Collection;

class BulkDelete
{
    public function __construct(
        protected PhotoBulkDelete $photoBulkDelete,
        protected HistoryBulkDelete $historyBulkDelete,
        protected VideoBulkDelete $videoBulkDelete
    ) {
    }

    public function handle(Collection $pages): bool|null
    {
        $photosCollection = collect();
        $historiesCollection = collect();
        $videosCollection = collect();

        $pages->each(
            function (ContentPage $page) use (
                $photosCollection,
                $videosCollection,
                $historiesCollection,
            ) {
                $page->photos->each(fn (ContentPhoto $photo) => $photosCollection->push($photo));
                $page->histories->each(fn (ContentHistory $history) => $historiesCollection->push($history));
                $page->videos->each(fn (ContentVideo $video) => $videosCollection->push($video));
            }
        );

        $this->photoBulkDelete->handle($photosCollection);
        $this->historyBulkDelete->handle($historiesCollection);
        $this->videoBulkDelete->handle($videosCollection);

        return ContentPage::whereIn('id', $pages->map(fn (ContentPage $page) => $page->getKey()))->delete();
    }
}
