<?php

namespace App\Actions\Media\Video;

use App\Actions\Action;
use App\Actions\File\Delete as FileDelete;
use App\Models\Media\MediaVideo;

class Delete extends Action
{
    public function __construct(protected FileDelete $delete)
    {
    }

    public function handle(MediaVideo $model): bool|null
    {
        if ($model->file) {
            $this->delete->handle($model->file);
        }

        return $model->delete();
    }
}
