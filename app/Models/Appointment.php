<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $table = 'appointment';
    protected $fillable = [
        'project_id',
        'prospect_id',
        'user_id',
        'app_note',
        'app_location',
        'app_time',
        'app_date'
    ];

    public function project()
    {
        return $this->hasOne(Project::class,'id');
    }

    public function prospect()
    {
        return $this->hasOne(Prospect::class,'id');
    }
}
