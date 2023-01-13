<?php

namespace App\Models\Profile;

use App\Models\Product\ProductMaster;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfileOwner extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'brand', 'bio', 'address', 'phone', 'email'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(ProductMaster::class, 'owner_id');
    }
}
