<?php

namespace App\Models\Content;

use App\Contracts\Model\HasPhotos;
use App\Contracts\Model\HasVideos;
use App\Models\Profile\ProfileAuthor;
use App\Traits\Model\Slug\SluggableByTitle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ContentArticle extends Model implements HasPhotos, HasVideos
{
    use HasFactory;
    use SluggableByTitle;

    protected $fillable = [
        'title',
        'content',
        'description',
        'category_id',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(ProfileAuthor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ContentCategory::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ContentHistory::class, 'master_id');
    }

    public function photos(): MorphMany
    {
        return $this->morphMany(ContentPhoto::class, 'content');
    }

    public function videos(): MorphMany
    {
        return $this->morphMany(ContentVideo::class, 'content');
    }
}
