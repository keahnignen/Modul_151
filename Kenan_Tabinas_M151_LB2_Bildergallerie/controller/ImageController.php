<?php
/**
 * Created by PhpStorm.
 * User: Keahnignen
 * Date: 03/06/2018
 * Time: 15:14
 */

class ImageController
{

    public function AddPicture($index, $gallery_id)
    {

        $filename = basename( $_FILES[$index]["name"]);

        $fileNameFragments = explode(".", $filename);

        $count = count($fileNameFragments);

        $extension = $fileNameFragments[$count-1];

        var_dump($_FILES[$index]);

        $check = getimagesize($_FILES[$index]["tmp_name"]);
        //$check = getimagesize($filename);


        if ($check === false) {
            return "<h1>File is not an Image</h1>";
        }

        $imageHash = hash("ripemd160", $filename . GlobalVariables::GetSessionId() . $gallery_id);

        $thumbnailHash = hash("ripemd160", $filename . GlobalVariables::GetSessionId() . $gallery_id . "thumbnail");

        $filename = $imageHash . "." . $extension;
        $thumbnailName = $thumbnailHash . "." . $extension;

        $targetPath = GlobalVariables::$ImagePath .  $filename;
        $thumbnailPath = GlobalVariables::$ImagePath .  $thumbnailName;

        if (file_exists($targetPath))
        {
            return "<h1>File already exists</h1>";
        }

        move_uploaded_file($_FILES[$index]["tmp_name"], $targetPath);


        $this->make_thumb($targetPath, $thumbnailPath, 200);

        $picture = new ImageModel($filename, $gallery_id, $thumbnailName);
        Repository::picture()->addPicture($picture);
        Dispatcher::moveTo(Singleton::getUrl()->ShowGallery($gallery_id));

        return null;

    }

   private function make_thumb($src, $dest, $desired_width) {

        /* read the source image */
        $source_image = imagecreatefromstring(file_get_contents($src));
        $width = imagesx($source_image);
        $height = imagesy($source_image);

        /* find the "desired height" of this thumbnail, relative to the desired width  */
        $desired_height = floor($height * ($desired_width / $width));

        /* create a new, "virtual" image */
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

        /* copy source image at a resized size */
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

        /* create the physical thumbnail image to its destination */

       imagecopy($virtual_image, $virtual_image, 0, 140, 0, 0, imagesx($virtual_image), imagesy($virtual_image));
       imagejpeg($virtual_image, $dest);

    }

}