<?php

namespace App\Models\Product;

use App\Contracts\Model\HasPhotos;
use App\Contracts\Model\HasSpecifications;
use App\Contracts\Model\HasVideos;
use App\Traits\Model\Product\HasSpecifications as ProductHasSpecifications;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProductVariant extends Model implements HasPhotos, HasSpecifications, HasVideos
{
    use HasFactory;
    use ProductHasSpecifications;

    protected $fillable = ['price', 'name', 'description'];

    public function master(): BelongsTo
    {
        return $this->belongsTo(ProductMaster::class);
    }

    public function photos(): MorphMany
    {
        return $this->morphMany(ProductPhoto::class, 'product');
    }

    public function videos(): MorphMany
    {
        return $this->morphMany(ProductVideo::class, 'product');
    }
}
