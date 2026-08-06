<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'date_of_birth',
        'marital_status',
        'join_date',
        'experience',
        'subjects',
        'certificates',
        'address',
        'notes',
    ];

    // پەیوەندی مامۆستا بە یوزەرەوە
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // پەیوەندی نێوان مامۆستا و وانەکان (ئەمەیە کە ئیرۆرەکەی دروستکردبوو)
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
