<?php

namespace App\Http\Controllers;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class CloudinaryStorage extends Controller
{
    public static function getPublicId($path)
    {
        $filename = pathinfo($path, PATHINFO_FILENAME); // Mendapatkan nama file tanpa ekstensi
        return $filename;
    }
}
