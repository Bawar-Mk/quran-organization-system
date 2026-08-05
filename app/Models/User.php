<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // <-- ئەمەمان زیاد کرد

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // <-- لێرەش وشەی HasRoles مان زیاد کرد

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // ئەمەمان زیاد کرد
    ];

    // پەیوەندی نێوان یوزەر و مامۆستا
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }
}
