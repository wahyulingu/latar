<?php

namespace App\Models\Product;

use App\Traits\Model\Slug\SluggableByName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    use HasFactory;
    use SluggableByName;

    protected $fillable = ['name', 'description'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo($this);
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany($this, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(ProductMaster::class);
    }
}
