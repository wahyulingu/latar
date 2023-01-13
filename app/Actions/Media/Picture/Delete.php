<?php

namespace App\Actions\Media\Picture;

use App\Actions\Action;
use App\Actions\File\Delete as FileDelete;
use App\Models\Media\MediaPicture;

class Delete extends Action
{
    public function __construct(protected FileDelete $delete)
    {
    }

    public function handle(MediaPicture $model): bool|null
    {
        if ($model->file) {
            $this->delete->handle($model->file);
        }

        return $model->delete();
    }
}
