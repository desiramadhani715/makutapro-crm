<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppLog extends Model
{
    use HasFactory;
    protected $table = 'app_log';
    protected $fillable = [
        'user_id',
        'description'
    ];
}
