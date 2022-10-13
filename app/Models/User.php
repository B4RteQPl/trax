<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, hasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function trips(): HasManyThrough
    {
        return $this->hasManyThrough(Trip::class, Car::class);
    }

    public function isAuthorized (): bool
    {
        return auth()->id() === $this->id;
    }

    public function isCarOwner(Car $car): bool
    {
        return $this->id === $car->user->id;
    }
}
