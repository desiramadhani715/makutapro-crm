<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fu extends Model
{
    use HasFactory;
    protected $table = 'fu';
    protected $fillable = [
        'prospect_id',
        'agent_id',
        'sales_id',
        'media_fu_id',
    ];

    public function prospect(){
        return $this->belongsTo(Prospect::class);
    }

    public function media()
    {
        return $this->belongsTo(MediaFU::class, 'media_fu_id');
    }
}
