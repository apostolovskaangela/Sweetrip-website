<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function drivers()
    {
        return $this->hasMany(User::class, 'manager_id')->where('role', 'driver');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function assignedTrips(): HasMany
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }

    public function createdTrips(): HasMany
    {
        return $this->hasMany(Trip::class, 'created_by');
    }

    public function isCeo(): bool
    {
        return $this->hasRole('ceo');
    }

    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    public function isDriver(): bool
    {
        return $this->hasRole('driver');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function managedVehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'manager_id');
    }
}
