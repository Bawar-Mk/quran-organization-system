<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'amount',
        'transaction_date',
        'notes',
        'user_id',
    ];

    // پەیوەندی بەو بەکارهێنەرەی (ئەدمین) کە تۆماری کردووە
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
