<?php

namespace App\Models\Product;

use App\Contracts\Model\HasPhotos;
use App\Contracts\Model\HasSpecifications;
use App\Contracts\Model\HasVideos;
use App\Models\Profile\ProfileOwner;
use App\Traits\Model\Product\HasSpecifications as ProductHasSpecifications;
use App\Traits\Model\Slug\SluggableByName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProductMaster extends Model implements HasPhotos, HasSpecifications, HasVideos
{
    use HasFactory;
    use SluggableByName;
    use ProductHasSpecifications;

    protected $fillable = [
        'name',
        'description',
        'category_id',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(ProfileOwner::class);
    }

    public function specifications(): MorphMany
    {
        return $this->morphMany(ProductSpecification::class, 'product');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'master_id');
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
