<?php

namespace App\Actions\Product\Photo;

use App\Actions\Media\Picture\BulkDelete as PictureBulkDelete;
use App\Models\Product\ProductPhoto;
use Illuminate\Support\Collection;

class BulkDelete
{
    public function __construct(protected PictureBulkDelete $pictureBulkDelete)
    {
    }

    public function handle(Collection $models): bool|null
    {
        $this->pictureBulkDelete->handle($models->map(fn (ProductPhoto $photo) => $photo->media));

        return ProductPhoto::whereIn('id', $models->map(fn (ProductPhoto $photo) => $photo->getKey()))->delete();
    }
}
