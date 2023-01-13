<?php

namespace App\Actions\Content\Video;

use App\Actions\Media\Picture\BulkDelete as PictureBulkDelete;
use App\Models\Content\ContentVideo;
use Illuminate\Support\Collection;

class BulkDelete
{
    public function __construct(protected PictureBulkDelete $pictureBulkDelete)
    {
    }

    public function handle(Collection $models): bool|null
    {
        $this->pictureBulkDelete->handle($models->map(fn (ContentVideo $video) => $video->media));

        return ContentVideo::whereIn('id', $models->map(fn (ContentVideo $video) => $video->getKey()))->delete();
    }
}
