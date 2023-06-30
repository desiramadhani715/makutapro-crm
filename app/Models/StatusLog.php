<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusLog extends Model
{
    use HasFactory;
    protected $table = 'status_log';
    protected $fillable = [
        'prospect_id',
        'status',
        'sales_id',
        'notes'
    ];
}
