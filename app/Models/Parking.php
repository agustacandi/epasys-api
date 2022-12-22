<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class, 'id', 'id_kendaraan');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'id_karyawan');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
