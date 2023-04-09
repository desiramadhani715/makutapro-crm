<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HistorySales extends Model
{
    use HasFactory;
    protected $table = 'history_sales';
    protected $fillable = [
        'project_id',
        'sales_id',
        'notes',
        'subject',
        'notes_dev',
        'subject_dev',
        'history_by',
    ];
    // public $timestamps = false;

    public static function SalesActivity(){
        return DB::table('history_sales as hs')
                ->join('project as p','p.id','hs.project_id')
                ->join('pt','pt.id','p.pt_id')
                ->join('sales as s','s.id','hs.sales_id')
                ->where('hs.history_by','Sales')
                ->where('pt.user_id', Auth::user()->id)
                ->select('p.nama_project','s.nama_sales','hs.notes_dev','hs.subject_dev','hs.created_at')
                ->orderBy('hs.id','desc')
                ->get();
    }
}
