<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'username', // <-- لێرەدا ئیمەیڵمان لابرد و یوسێرنەیممان دانا
        'password',
        'role',
    ];

    // پەیوەندی نێوان یوزەر و مامۆستا
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }
}
