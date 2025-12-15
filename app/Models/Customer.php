<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'email',
        'password',
        'full_name',
        'phone',
        'avatar',
        'google_id',
        'facebook_id',
        'points',
        'address',
        'city',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'points' => 'integer',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if customer has a password set.
     * Google-only accounts have null password until they set one.
     */
    public function hasPassword(): bool
    {
        return $this->getAttributes()['password'] !== null;
    }
}
