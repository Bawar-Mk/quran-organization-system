<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // ڕێگەدان بە پاشەکەوتکردنی ئەم خانانە
    protected $fillable = [
        'full_name',
        'gender',
        'date_of_birth',
        'education_level',
        'phone_number',
        'address',
        'join_date',
        'study_type',
        'score',
        'marital_status',
    ];
}
