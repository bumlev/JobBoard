<?php

namespace App\Http\Controllers;

use App\Repositories\FilesCtrlRepos\GetSoredImage\Classes\GetSoredImage;

class FilesController extends Controller
{
   // Get a stored Image
    public function getStoredImage($imageName)
    {
        $response = GetSoredImage::execute($imageName);
        return $response;
    }
}
