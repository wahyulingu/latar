<?php

namespace App\Models\Profile;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileCustomer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'bio', 'address', 'phone', 'email'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
