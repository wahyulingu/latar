<?php

namespace App\Actions\Content\Photo;

use App\Actions\Action;
use App\Actions\Media\Picture\Delete as PictureDelete;
use App\Models\Content\ContentPhoto;

class Delete extends Action
{
    public function __construct(protected PictureDelete $pictureDelete)
    {
    }

    public function handle(ContentPhoto $model): bool
    {
        if ($model->media) {
            $this->pictureDelete->handle($model->media);
        }

        return $model->delete();
    }
}
