<?php

namespace App\Actions\Product\Video;

use App\Actions\Media\Picture\BulkDelete as PictureBulkDelete;
use App\Models\Product\ProductVideo;
use Illuminate\Support\Collection;

class BulkDelete
{
    public function __construct(protected PictureBulkDelete $pictureBulkDelete)
    {
    }

    public function handle(Collection $models): bool|null
    {
        $this->pictureBulkDelete->handle($models->map(fn (ProductVideo $video) => $video->media));

        return ProductVideo::whereIn('id', $models->map(fn (ProductVideo $video) => $video->getKey()))->delete();
    }
}
