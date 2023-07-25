<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Advertiser extends Model
{
    use HasFactory;
    protected $table = 'advertiser';
    protected $fillable = [
        'pt_id',
        'advertiser',
        'created_by',
        'updated_by'
    ];

    public static function getAdv(){
        return DB::table('advertiser')
                ->join('pt','pt.id','advertiser.pt_id')
                ->select('advertiser.*')
                ->get();
    }
}
