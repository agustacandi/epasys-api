<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'body',
        'img_url',
        'id_karyawan'
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

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'id_karyawan');
    }
}
