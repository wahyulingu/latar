<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $fillable = ['icon', 'name', 'description'];

    public function product(): MorphTo
    {
        return $this->morphTo();
    }
}
