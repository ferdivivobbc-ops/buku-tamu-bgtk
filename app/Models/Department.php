<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'pic_name',
        'room_location',
        'description',
        'is_active',
        'is_available',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_available' => 'boolean',
    ];

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }
}
