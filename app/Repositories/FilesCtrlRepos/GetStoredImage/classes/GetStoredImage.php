<?php
namespace App\Repositories\FilesCtrlRepos\GetStoredImage\classes;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GetStoredImage
{
    static public function execute($imageName)
    {
        $imagePath = 'public/images/'.$imageName;
        $extensions = ["jpeg" , "jpg" , "png" , "gif"];
        $extension = pathinfo($imageName , PATHINFO_EXTENSION);

        // Check if the image exists
        if (Storage::exists($imagePath)) {

            // Retrieve the image contents
            $imageContents = Storage::get($imagePath);

            if(in_array($extension , $extensions))
            {
                $resizedImage= Image::make($imageContents)->resize(170 , 170);
                $imageContents = $resizedImage->encode();
            }

            // Set the response headers
            $headers = [
                'Content-Type' => Storage::mimeType($imagePath),
            ]; 
            
            // You can now use the $imageContents variable as needed (e.g., return it as a response or process it further)
            return Response($imageContents, 200, $headers);
        }
        return null;
    }
}