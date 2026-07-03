<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Service model properties for static analysis
 *
 * @property int $id
 * @property int $provider_id
 * @property int|null $location_id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property string|null $category
 * @property string $status
 * @property int $rating
 * @property string|null $image_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Service extends Model
{
    use HasFactory;

    public ?string $image_url = null;

    protected static function booted()
    {
        // When deleting a service, remove stored image if present
        static::deleting(function (Service $service) {
            if ($service->image_url) {
                try {
                    $path = self::getStoragePathFromUrl($service->image_url);
                    if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                    }
                } catch (\Throwable $e) {
                    // Ignore deletion errors
                }
            }
        });

        // When updating and image_url changed, delete the previous image file
        static::updating(function (Service $service) {
            if ($service->isDirty('image_url')) {
                $old = $service->getOriginal('image_url');
                if ($old) {
                    try {
                        $path = self::getStoragePathFromUrl($old);
                        if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                        }
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }
            }
        });
    }

    protected static function getStoragePathFromUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        // If URL contains full app URL, parse path
        $parsed = parse_url($url);
        $path = $parsed['path'] ?? $url;

        // Remove leading /storage/ if present because files are stored in disk 'public' root
        $prefix = '/storage/';
        if (str_starts_with($path, $prefix)) {
            return ltrim(substr($path, strlen($prefix)), '/');
        }

        // If path already points to storage root without prefix
        return ltrim($path, '/');
    }

    protected $fillable = [
        'provider_id',
        'location_id',
        'name',
        'description',
        'price',
        'category',
        'status',
        'rating',
        'image_url',
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
