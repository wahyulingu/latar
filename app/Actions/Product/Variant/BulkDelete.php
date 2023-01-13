<?php

namespace App\Actions\Product\Variant;

use App\Actions\Product\Photo\BulkDelete as PhotoBulkDelete;
use App\Actions\Product\Video\BulkDelete as VideoBulkDelete;
use App\Models\Product\ProductPhoto;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductVideo;
use Illuminate\Support\Collection;

class BulkDelete
{
    public function __construct(
        protected PhotoBulkDelete $photoBulkDelete,
        protected VideoBulkDelete $videoBulkDelete
    ) {
    }

    public function handle(Collection $variants): bool|null
    {
        $photosCollection = collect();
        $videosCollection = collect();

        $variants->each(
            function (ProductVariant $variant) use ($photosCollection, $videosCollection) {
                $variant->photos->each(fn (ProductPhoto $photo) => $photosCollection->push($photo));
                $variant->videos->each(fn (ProductVideo $video) => $videosCollection->push($video));
            }
        );

        $this->photoBulkDelete->handle($photosCollection);
        $this->videoBulkDelete->handle($videosCollection);

        return ProductVariant::whereIn('id', $variants->map(fn (ProductVariant $variant) => $variant->getKey()))->delete();
    }
}
