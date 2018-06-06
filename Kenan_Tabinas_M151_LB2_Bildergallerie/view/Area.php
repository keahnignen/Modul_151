<?php
/**
 * Created by PhpStorm.
 * User: Kenan
 * Date: 25/04/2018
 * Time: 12:56
 */

class Area
{

    public function Login()
    {
        $fileName = file_get_contents('..\view\html\login.html');

        $file = str_replace("<!--login-->", "action='\\" . Singleton::getUrl()->Login . "'", $fileName);

        $file = str_replace("<!--register-->", "action='\\" . Singleton::getUrl()->Register . "'", $file);

        return $file;
    }



    public static function DisplayLoggedIn()
    {
        $content = "";
        $content .= "<a href='\\". Singleton::getUrl()->NewGallery ."'>";
        $content .= "<div class='postBox'>";
        $content .= "<h1>Create new Gallery</h1>";
        $content .= "</div>";
        $content .= "</a>;";


        $content .=  View::getLinkBox(Singleton::getUrl()->deleteUser, "Delete User");

        $content .= "<h1>Galleries</h1><br><br>";

        $allGalleriesByUser = Repository::gallery()->getAllGalleriesByUserId(GlobalVariables::GetSessionId());
        foreach ($allGalleriesByUser as $gallery)
        {
            if ($gallery instanceof GalleryModel)
            {
                $content .= "<a href='\\". Singleton::getUrl()->ShowGallery ."\\". $gallery->id ."'>";
                $content .= "<div class='postBox'>";
                $content .= "<h1>". $gallery->name ."</h1>";
                $content .= "<h2>". $gallery->description ."</h2>";
                $content .= "</div>";
                $content .= "</a>;";
            }


        }



        return $content;

    }

    public function DeleteUser()
    {
        $fileName = file_get_contents('..\view\html\area\deleteUser.html');

        $file = str_replace("<!--login-->", "action='\\" . Singleton::getUrl()->deleteUser . "'", $fileName);

        return $file;


    }


}