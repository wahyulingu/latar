<?php

namespace App\Models;

use App\Traits\Model\Slug\SluggableByName;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;
    use SluggableByName;

    protected $fillable = ['name', 'description', 'path'];

    protected $appends = ['url'];

    protected function url(): Attribute
    {
        return new Attribute(
            get: fn () => $this->path

                ? static::fileSystemAdapter()->url($this->path)
                : null
        );
    }

    /**
     * Get the disk that profile photos should be stored on.
     *
     * @return string
     */
    public static function fileDisk()
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : config('app.file_disk', 'public');
    }

    public static function fileSystemAdapter(): FilesystemAdapter
    {
        return Storage::disk(static::fileDisk());
    }

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
