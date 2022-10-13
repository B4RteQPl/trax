<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'make',
        'model',
        'year',
        'user_id'
    ];

    protected $casts = [
        'year' => 'int'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public function isAssignedToUser($user): bool
    {
        return $this->user_id === $user->id;
    }

    public function getTotalMiles(): float
    {
        return $this->trips()->sum('miles');
    }

    public function getTotalCount(): int
    {
        return $this->trips()->count();
    }

    public function scopeOfAuthorizedUser($query)
    {
        return $query->where('user_id', '=', auth()->id());
    }
}
