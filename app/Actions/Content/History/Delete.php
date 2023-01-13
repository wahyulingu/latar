<?php

namespace App\Actions\Content\History;

use App\Actions\Action;
use App\Actions\Content\Photo\BulkDelete as PhotoBulkDelete;
use App\Actions\Content\Video\BulkDelete as VideoBulkDelete;
use App\Models\Content\ContentHistory;

class Delete extends Action
{
    public function __construct(
        protected PhotoBulkDelete $photoBulkDelete,
        protected VideoBulkDelete $videoBulkDelete
    ) {
    }

    public function handle(ContentHistory $model): bool
    {
        $this->photoBulkDelete->handle($model->photos);
        $this->videoBulkDelete->handle($model->videos);

        return $model->delete();
    }
}
