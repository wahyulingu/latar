<?php

namespace App\Actions\Media\Picture;

use App\Actions\File\BulkDelete as FileBulkDelete;
use App\Models\Media\MediaPicture;
use Illuminate\Support\Collection;

class BulkDelete
{
    public function __construct(protected FileBulkDelete $fileBulkDelete)
    {
    }

    public function handle(Collection $models): bool|null
    {
        $this->fileBulkDelete->handle($models->map(fn (MediaPicture $media) => $media->file));

        return MediaPicture::whereIn('id', $models->map(fn (MediaPicture $media) => $media->getKey()))->delete();
    }
}
