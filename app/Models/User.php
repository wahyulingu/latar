<?php

namespace App\Models;

use App\Contracts\Model\HasPermissions as ModelHasPermissions;
use App\Contracts\Model\HasRoles as ModelHasRoles;
use App\Models\Profile\ProfileAuthor;
use App\Models\Profile\ProfileCustomer;
use App\Models\Profile\ProfileDriver;
use App\Models\Profile\ProfileOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements ModelHasPermissions, ModelHasRoles
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasPermissions;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function authorProfile(): HasOne
    {
        return $this->hasOne(ProfileAuthor::class);
    }

    public function driverProfile(): HasOne
    {
        return $this->hasOne(ProfileDriver::class);
    }

    public function ownerProfile(): HasOne
    {
        return $this->hasOne(ProfileOwner::class);
    }

    public function customerProfile(): HasOne
    {
        return $this->hasOne(ProfileCustomer::class);
    }
}
