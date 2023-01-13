<?php

namespace App\Models\Media;

use App\Contracts\Model\HasFile;
use App\Models\File;
use App\Traits\Model\Slug\SluggableByName;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MediaVideo extends Model implements HasFile
{
    use HasFactory;
    use SluggableByName;

    protected $fillable = ['name', 'description'];

    protected $appends = ['url'];

    protected function url(): Attribute
    {
        return new Attribute(
            get: fn () => $this->file->url
        );
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function videoable(): MorphTo
    {
        return $this->morphTo();
    }
}
