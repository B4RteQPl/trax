<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'miles',
        'total',
        'car_id'
    ];

    protected $dates = [
        'date'
    ];

    protected $casts = [
        'miles' => 'float',
        'total' => 'float',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
