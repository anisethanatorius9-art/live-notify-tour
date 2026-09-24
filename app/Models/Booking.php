<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tourist_id
 * @property int|null $service_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $booking_date
 * @property string|null $booking_time
 * @property int $number_of_people
 * @property float $total_price
 * @property string|null $notes
 */
class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'tourist_id',
        'service_id',
        'booking_type',
        'transport_type',
        'origin',
        'destination',
        'origin_latitude',
        'origin_longitude',
        'destination_latitude',
        'destination_longitude',
        'distance_km',
        'duration_minutes',
        'status',
        'booking_date',
        'booking_time',
        'number_of_people',
        'total_price',
        'notes',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'booking_date' => 'date',
        'origin_latitude' => 'float',
        'origin_longitude' => 'float',
        'destination_latitude' => 'float',
        'destination_longitude' => 'float',
        'distance_km' => 'float',
    ];

    /**
     * Get the tourist who made this booking
     */
    public function tourist()
    {
        return $this->belongsTo(User::class, 'tourist_id');
    }

    /**
     * Get the service that was booked
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the payment for this booking
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Check if booking is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if booking is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if booking is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
