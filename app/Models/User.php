<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active', 'otp_code', 'otp_expires_at',
        'trip_plan_quota', 'trip_plan_generated_count',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at'    => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public function isActive(): bool {
        return $this->is_active === true;
    }
}