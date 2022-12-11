<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vechile extends Model
{
    use HasFactory;

    protected $fillable = [
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
