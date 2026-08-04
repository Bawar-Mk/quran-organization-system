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
        'username',
        'password',
        'teacher_id',
    ];

    // ... کۆدەکانی تر وەک خۆی لێ بگەڕێ
}
