<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iotdbandeng extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'power',
        'panjang',
        'berat',
        'harga',
    ];

    protected $primaryKey = 'id';
    public $incrementing = false;

    public function getKeyType()
    {
        return 'string';
    }
    // // satu alat IOT bisa diakses oleh banyak mitra
    // public function mitra()
    // {
    //     return $this->hasMany(Mitra::class);
    // }
}
