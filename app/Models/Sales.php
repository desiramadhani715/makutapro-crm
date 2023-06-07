<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $table = 'sales';
    protected $fillable = [
        'user_id',
        'project_id',
        'agent_id',
        'kode_sales',
        'nama_sales',
        'sort',
        'token_fcm',
        'device_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->hasMany(Project::class, 'project_id');
    }

    public static function getNextSalesSort($agentId)
    {
        $currentSort = 0;

        // Mengambil nilai sort dari sales saat ini
        $currentSales = Sales::where('agent_id', $agentId)->orderBy('id', 'desc')->first();
        if ($currentSales) {
            $currentSort = $currentSales->sort;
        }

        // Mendapatkan nilai sort untuk sales selanjutnya
        $nextSales = Sales::where('sort', '>', $currentSort)->where('agent_id', $agentId)->orderBy('sort', 'asc')->first();
        $nextSort = $currentSort;

        if ($nextSales) {
            $nextSort = $nextSales->sort;
        }

        return $nextSort;
    }

}
