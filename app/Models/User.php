<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'nim',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'avatar',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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

    public function parking()
    {
        return $this->hasMany(Parking::class, 'id_user', 'id');
    }

    public function vechiles()
    {
        return $this->hasMany(Vechile::class, 'id_user', 'id');
    }
}
