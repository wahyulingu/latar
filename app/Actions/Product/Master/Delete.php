<?php

namespace App\Actions\Product\Master;

use App\Actions\Action;
use App\Actions\Product\Photo\BulkDelete as PhotoBulkDelete;
use App\Actions\Product\Variant\BulkDelete as VariantBulkDelete;
use App\Actions\Product\Video\BulkDelete as VideoBulkDelete;
use App\Models\Product\ProductMaster;

class Delete extends Action
{
    public function __construct(
        protected PhotoBulkDelete $photoBulkDelete,
        protected VariantBulkDelete $variantBulkDelete,
        protected VideoBulkDelete $videoBulkDelete
    ) {
    }

    public function handle(ProductMaster $model): bool
    {
        $this->photoBulkDelete->handle($model->photos);
        $this->variantBulkDelete->handle($model->variants);
        $this->videoBulkDelete->handle($model->videos);

        return $model->delete();
    }
}
