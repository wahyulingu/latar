<?php

namespace App\Models\Profile;

use App\Models\Content\ContentArticle;
use App\Models\Content\ContentPage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfileDriver extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'bio', 'address', 'phone', 'email'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(ContentArticle::class, 'driver_id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(ContentPage::class, 'driver_id');
    }
}
