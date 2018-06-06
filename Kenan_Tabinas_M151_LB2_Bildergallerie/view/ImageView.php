<?php
/**
 * Created by PhpStorm.
 * User: Keahnignen
 * Date: 03/06/2018
 * Time: 15:38
 */

class ImageView
{

    public function DisplayImage()
    {

        $filename = GlobalVariables::getUriFragments(1);


        if ($filename == null)
        {
            Dispatcher::moveTo(Singleton::getUrl()->Homepage);
        }

        $path = GlobalVariables::$ImagePath . $filename;

        if (!file_exists($path))
        {
            View::ErrorMessage("Error 404 - Image not found");
            return;
        }



        $image = imagecreatefromstring(file_get_contents($path));



        header('Content-Type: image/png');

       // Output the image
       imagepng($image);

       // Free up memory
       imagedestroy($image);
       exit;

    }

    /**
     * @param $imageModel ImageModel
     * @return string
     */
    public function DisplayImageAsBox($imageModel)
    {
        $content = "";
        $content .= "<div class='userBox'>";
        $content .= "<img src='/". Singleton::getUrl()->getImagePath($imageModel->path_thumbnail)  ."' class='img-thumbnail'/>";
        $content .= "</div>";
        return $content;
    }

}