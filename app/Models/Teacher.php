<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'subjects',
        'certificates',
        'phone_number',
        'date_of_birth',
        'address',
        'join_date',
        'experience',
        'marital_status',
        'notes',
    ];
}
