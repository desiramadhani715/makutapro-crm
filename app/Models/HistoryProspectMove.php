<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryProspectMove extends Model
{
    use HasFactory;
    protected $table = 'history_prospect_move';
    protected $fillable = [
        'prospect_id',
        'project_id',
        'next_agent_id',
        'next_sort_agent',
        'prev_agent_id',
        'prev_sort_agent',
        'next_sales_id',
        'next_sort_sales',
        'prev_sales_id',
        'prev_sort_sales'
    ];
    public $timestamps = ["created_at"];
    const UPDATED_AT = NULL;

}
