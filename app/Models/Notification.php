<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'type',
        'lu'
    ];

    protected $casts = [
        'lu' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
