<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the services offered in this location
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
