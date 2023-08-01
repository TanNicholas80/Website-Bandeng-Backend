<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mitra;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nmProduk',
        'foto_produk',
        'hrgProduk',
        'stok',
        'beratProduk',
        'dskProduk'
    ];
    // satu produk dimiliki oleh satu mitra
    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }
}
