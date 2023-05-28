<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotesAdmin extends Model
{
    use HasFactory;
    protected $table = 'notes_admin';
    protected $fillable = [
        'project_id',
        'user_id',
        'prospect_id',
        'notes',
    ];

    public function prospect(){
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }
}
