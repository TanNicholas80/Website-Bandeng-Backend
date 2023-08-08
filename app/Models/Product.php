<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mitra;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasFactory, HasApiTokens, HasUuids;

    protected $fillable = [
        'nmProduk',
        'foto_produk',
        'hrgProduk',
        'stok',
        'beratProduk',
        'dskProduk'
    ];

    protected $primaryKey = 'id';
    public $incrementing = false;

    public function getKeyType()
    {
        return 'string';
    }
    // satu produk dimiliki oleh satu mitra
    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }
}
