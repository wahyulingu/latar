<?php

namespace App\Actions\Product\Master;

use App\Actions\Product\Photo\BulkDelete as PhotoBulkDelete;
use App\Actions\Product\Variant\BulkDelete as VariantBulkDelete;
use App\Actions\Product\Video\BulkDelete as VideoBulkDelete;
use App\Models\Product\ProductMaster;
use App\Models\Product\ProductPhoto;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductVideo;
use Illuminate\Support\Collection;

class BulkDelete
{
    public function __construct(
        protected PhotoBulkDelete $photoBulkDelete,
        protected VariantBulkDelete $variantBulkDelete,
        protected VideoBulkDelete $videoBulkDelete
    ) {
    }

    public function handle(Collection $masters): bool|null
    {
        $photosCollection = collect();
        $variantsCollection = collect();
        $videosCollection = collect();

        $masters->each(
            function (ProductMaster $master) use (
                $photosCollection,
                $videosCollection,
                $variantsCollection,
            ) {
                $master->photos->each(fn (ProductPhoto $photo) => $photosCollection->push($photo));
                $master->variants->each(fn (ProductVariant $variant) => $variantsCollection->push($variant));
                $master->videos->each(fn (ProductVideo $video) => $videosCollection->push($video));
            }
        );

        $this->photoBulkDelete->handle($photosCollection);
        $this->variantBulkDelete->handle($variantsCollection);
        $this->videoBulkDelete->handle($videosCollection);

        return ProductMaster::whereIn('id', $masters->map(fn (ProductMaster $master) => $master->getKey()))->delete();
    }
}
