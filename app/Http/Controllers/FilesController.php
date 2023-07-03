<?php

namespace App\Http\Controllers;

use App\Repositories\FilesCtrlRepos\GetStoredImage\Classes\GetStoredImage;

class FilesController extends Controller
{
   // Get a stored Image
    public function getStoredImage($imageName)
    {
        $response = GetStoredImage::execute($imageName);
        return $response;
    }
}
