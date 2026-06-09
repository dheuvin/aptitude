<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'phone',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    public function testAttempts()
    {
        return $this->hasMany(TestAttempt::class);
    }
    public function isAdmin()
{
    return $this->role === 'admin';
}
}
