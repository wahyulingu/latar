<?php

namespace App\Actions\Product\Photo;

use App\Actions\Action;
use App\Actions\Media\Picture\Delete as PictureDelete;
use App\Models\Product\ProductPhoto;

class Delete extends Action
{
    public function __construct(protected PictureDelete $pictureDelete)
    {
    }

    public function handle(ProductPhoto $model): bool
    {
        if ($model->media) {
            $this->pictureDelete->handle($model->media);
        }

        return $model->delete();
    }
}
