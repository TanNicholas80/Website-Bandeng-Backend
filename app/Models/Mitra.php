<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Product;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Mitra extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, HasUuids;

    protected $fillable = [
        'namaLengkap',
        'namaMitra',
        'alamatMitra',
        'tglLahir',
        'jeniskel',
        'no_tlp',
        'foto_mitra',
        'email',
        'email_verified_at',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $primaryKey = 'id';
    public $incrementing = false;

    public $timestamps = false;

    public function getKeyType()
    {
        return 'string';
    }
    // satu mitra dapat memiliki banyak produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
