<?php

namespace App\Actions\Content\Video;

use App\Actions\Action;
use App\Actions\Media\Video\Delete as VideoDelete;
use App\Models\Content\ContentVideo;

class Delete extends Action
{
    public function __construct(protected VideoDelete $videoDelete)
    {
    }

    public function handle(ContentVideo $model): bool
    {
        if ($model->media) {
            $this->videoDelete->handle($model->media);
        }

        return $model->delete();
    }
}
