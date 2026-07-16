<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'role',
        'status',
        'nim',
        'phone',
        'address',
        'gender',
        'is_approved',
        'membership_status',
        'premium_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
            'premium_until' => 'date',
        ];
    }

    public function isPremium(): bool
    {
        if (in_array($this->role, ['admin', 'petugas'])) {
            return true;
        }

        if (empty($this->membership_status) || $this->membership_status !== 'premium') {
            return false;
        }

        if (!$this->premium_until) {
            return true;
        }

        return $this->premium_until->endOfDay()->isFuture();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'member_id');
    }
}
