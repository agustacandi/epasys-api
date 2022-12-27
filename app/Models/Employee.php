<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Model
{
    use HasApiTokens, HasFactory;

    protected $guarded = [
        'id'
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

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
