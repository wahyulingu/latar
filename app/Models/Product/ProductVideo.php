<?php

namespace App\Models\Product;

use App\Contracts\Model\HasMedia;
use App\Models\Media\MediaVideo;
use App\Traits\Model\Slug\SluggableByName;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProductVideo extends Model implements HasMedia
{
    use HasFactory;
    use SluggableByName;

    protected $fillable = ['name', 'description'];

    protected $appends = ['url'];

    protected function url(): Attribute
    {
        return new Attribute(
            get: fn () => $this->media->url
        );
    }

    public function media(): MorphOne
    {
        return $this->morphOne(MediaVideo::class, 'videoable');
    }

    public function product(): MorphTo
    {
        return $this->morphTo();
    }
}
