<?php

namespace App\Actions\Product\Variant;

use App\Actions\Action;
use App\Actions\Product\Photo\BulkDelete as PhotoBulkDelete;
use App\Actions\Product\Video\BulkDelete as VideoBulkDelete;
use App\Models\Product\ProductVariant;

class Delete extends Action
{
    public function __construct(
        protected PhotoBulkDelete $photoBulkDelete,
        protected VideoBulkDelete $videoBulkDelete
    ) {
    }

    public function handle(ProductVariant $model): bool
    {
        $this->photoBulkDelete->handle($model->photos);
        $this->videoBulkDelete->handle($model->videos);

        return $model->delete();
    }
}
