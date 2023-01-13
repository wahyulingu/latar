<?php

namespace App\Actions\Profile\Customer;

use App\Actions\Action;
use App\Actions\Content\History\BulkDelete as HistoryBulkDelete;
use App\Actions\Content\Photo\BulkDelete as PhotoBulkDelete;
use App\Actions\Content\Video\BulkDelete as VideoBulkDelete;

class Delete extends Action
{
    public function __construct(
        protected PhotoBulkDelete $photoBulkDelete,
        protected HistoryBulkDelete $historyBulkDelete,
        protected VideoBulkDelete $videoBulkDelete
    ) {
    }

    public function handle($model): bool
    {
        $this->photoBulkDelete->handle($model->photos);
        $this->historyBulkDelete->handle($model->histories);
        $this->videoBulkDelete->handle($model->videos);

        return $model->delete();
    }
}
