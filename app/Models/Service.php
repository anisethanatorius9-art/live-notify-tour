<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'location_id',
        'name',
        'description',
        'price',
        'category',
        'status',
        'rating',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the provider (user) who offers this service
     */
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    /**
     * Get the location where this service is offered
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the bookings for this service
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if service is available (active)
     */
    public function isAvailable(): bool
    {
        return $this->status === 'active';
    }
}
