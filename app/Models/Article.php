<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Article extends Model
{
    use HasFactory, HasApiTokens, HasUuids;

    protected $fillable = [
        'jdlArticle',
        'isiArticle',
        'foto_article'
    ];

    protected $primaryKey = 'id';
    public $incrementing = false;

    public function getKeyType()
    {
        return 'string';
    }
}
