<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenFcm extends Model
{
    use HasFactory;
    protected $table = 'token_fcm';
    protected $fillable = [
        'user_id',
        'device_id',
        'token_fcm'
    ];
}
