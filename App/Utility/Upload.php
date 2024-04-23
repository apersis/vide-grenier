<?php

namespace App\Utility;

class Upload {

    // TLR : issue #19 -> image volumineuse
    public static function uploadFile($file, $fileName)
    {
        $currentDirectory = getcwd();
        $uploadDirectory = "/storage/";


        $fileExtensionsAllowed = ['jpeg', 'jpg', 'png'];

        $fileSize = $file['size'];
        $fileTmpName = $file['tmp_name'];

        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $pictureName = basename($fileName . '.'. $fileExtension);


        $uploadPath = $currentDirectory . $uploadDirectory . $pictureName;
        
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
        
        if ($didUpload) {
            return $pictureName;
        }
    }
}
