<?php

namespace App\Models;

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

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
