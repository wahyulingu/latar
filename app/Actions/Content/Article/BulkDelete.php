<?php

namespace App\Actions\Content\Article;

use App\Actions\Content\History\BulkDelete as HistoryBulkDelete;
use App\Actions\Content\Photo\BulkDelete as PhotoBulkDelete;
use App\Actions\Content\Video\BulkDelete as VideoBulkDelete;
use App\Models\Content\ContentArticle;
use App\Models\Content\ContentHistory;
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

    public function handle(Collection $articles): bool|null
    {
        $photosCollection = collect();
        $historiesCollection = collect();
        $videosCollection = collect();

        $articles->each(
            function (ContentArticle $article) use (
                $photosCollection,
                $videosCollection,
                $historiesCollection,
            ) {
                $article->photos->each(fn (ContentPhoto $photo) => $photosCollection->push($photo));
                $article->histories->each(fn (ContentHistory $history) => $historiesCollection->push($history));
                $article->videos->each(fn (ContentVideo $video) => $videosCollection->push($video));
            }
        );

        $this->photoBulkDelete->handle($photosCollection);
        $this->historyBulkDelete->handle($historiesCollection);
        $this->videoBulkDelete->handle($videosCollection);

        return ContentArticle::whereIn('id', $articles->map(fn (ContentArticle $article) => $article->getKey()))->delete();
    }
}
