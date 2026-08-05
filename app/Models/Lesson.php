<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'teacher_id',
        'start_date',
        'end_date',
        'schedule',
        'passing_score',
        'status',
        'notes'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class)->withPivot('is_paid', 'score')->withTimestamps();
    }
}
