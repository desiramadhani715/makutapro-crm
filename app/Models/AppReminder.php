<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppReminder extends Model
{
    use HasFactory;
    protected $table = 'app_reminders';
    protected $fillable = [
        'appointment_id',
        'time_period',
        'app_time',
        'app_date'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

}
