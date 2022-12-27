<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'merek',
        'no_polisi',
        'foto_stnk',
        'foto_kendaraan',
        'id_user',
    ];

    public function getCreatedAtAttribute($value)
    {
        $date = Carbon::parse($value)->setTimezone('Asia/Jakarta');
        return $date->format('Y-m-d H:i');
    }
    public function getUpdatedAtAttribute($value)
    {
        $date = Carbon::parse($value)->setTimezone('Asia/Jakarta');
        return $date->format('Y-m-d H:i');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
