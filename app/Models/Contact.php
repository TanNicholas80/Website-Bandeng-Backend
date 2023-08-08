<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Contact extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'nameCn',
        'emailCn',
        'pesanCn',
        'kategoriCn'
    ];

    protected $primaryKey = 'id';
    public $incrementing = false;

    public function getKeyType()
    {
        return 'string';
    }
}
