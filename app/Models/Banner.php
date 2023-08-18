<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $table = 'banner';
    protected $filable = [
        'project_id',
        'title',
        'subtitle',
        'banner',
        'description',
    ];
}
