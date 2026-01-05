<?php

namespace App\Models;

use App\TripStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_number',
        'vehicle_id',
        'driver_id',
        'a_code',
        'destination_from',
        'destination_to',
        'status',
        'mileage',
        'cmr',
        'driver_description',
        'admin_description',
        'trip_date',
        'invoice_number',
        'amount',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => TripStatus::class,
            'trip_date' => 'date',
            'mileage' => 'decimal:2',
            'amount' => 'decimal:2',
        ];
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function stops(): HasMany
    {
        return $this->hasMany(TripStop::class)->orderBy('stop_order');
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    public function getCmrUrlAttribute()
{
    return $this->cmr ? asset('storage/' . $this->cmr) : null;
}
}
