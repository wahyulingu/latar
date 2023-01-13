<?php

namespace App\Actions\File;

use App\Models\File;
use Illuminate\Support\Collection;

class BulkDelete
{
    public function handle(Collection $models): bool|null
    {
        $models->each(fn (File $file) => $file->path && File::fileSystemAdapter()->delete($file->path));

        return File::whereIn('id', $models->map(fn (File $file) => $file->getKey()))->delete();
    }
}
