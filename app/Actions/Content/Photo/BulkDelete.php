<?php

namespace App\Actions\Content\Photo;

use App\Actions\Media\Picture\BulkDelete as PictureBulkDelete;
use App\Models\Content\ContentPhoto;
use Illuminate\Support\Collection;

class BulkDelete
{
    public function __construct(protected PictureBulkDelete $pictureBulkDelete)
    {
    }

    public function handle(Collection $models): bool|null
    {
        $this->pictureBulkDelete->handle($models->map(fn (ContentPhoto $photo) => $photo->media));

        return ContentPhoto::whereIn('id', $models->map(fn (ContentPhoto $photo) => $photo->getKey()))->delete();
    }
}
