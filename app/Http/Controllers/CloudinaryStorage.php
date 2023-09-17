<?php

namespace App\Http\Controllers;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class CloudinaryStorage extends Controller
{
    function getCloudinaryImageInfo($imageUrl) {
        // Ambil public ID dari URL
        $publicId = Cloudinary::getPublicId($imageUrl);
    
        return $publicId;
    }
}
