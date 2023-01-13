<?php

namespace App\Actions\Product\Video;

use App\Actions\Action;
use App\Actions\Media\Video\Delete as VideoDelete;
use App\Models\Product\ProductVideo;

class Delete extends Action
{
    public function __construct(protected VideoDelete $videoDelete)
    {
    }

    public function handle(ProductVideo $model): bool
    {
        if ($model->media) {
            $this->videoDelete->handle($model->media);
        }

        return $model->delete();
    }
}
