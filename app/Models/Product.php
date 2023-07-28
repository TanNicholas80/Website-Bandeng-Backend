<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nmProduk',
        'hrgProduk',
        'stok',
        'beratProduk',
        'dskProduk'
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }

}
