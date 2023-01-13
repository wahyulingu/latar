<?php

namespace App\Actions\File;

use App\Models\File;

class Delete
{
    public function handle(File $model): bool|null
    {
        if ($model->path) {
            File::fileSystemAdapter()->delete($model->path);
        }

        return $model->delete();
    }
}
