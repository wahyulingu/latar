<?php

namespace App\Actions\Media\Video;

use App\Actions\File\BulkDelete as FileBulkDelete;
use App\Models\Media\MediaVideo;
use Illuminate\Support\Collection;

class BulkDelete
{
    public function __construct(protected FileBulkDelete $fileBulkDelete)
    {
    }

    public function handle(Collection $models): bool|null
    {
        $this->fileBulkDelete->handle($models->map(fn (MediaVideo $media) => $media->file));

        return MediaVideo::whereIn('id', $models->map(fn (MediaVideo $media) => $media->getKey()))->delete();
    }
}
