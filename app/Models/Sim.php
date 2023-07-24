<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sim extends Model
{
    use HasFactory;

    protected $fillable = [
        'iccid',
        'msisdn',
        'status',
        'rate',
        'voice',
        'data',
        'sms',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
