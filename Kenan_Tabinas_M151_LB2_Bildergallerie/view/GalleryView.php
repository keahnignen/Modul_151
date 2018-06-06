<?php
/**
 * Created by PhpStorm.
 * User: Keahnignen
 * Date: 27/05/2018
 * Time: 16:36
 */

class GalleryView
{

    public function CreateNewGallery($error = null)
    {
        return $this->EditGallery(Singleton::getUrl()->SaveGallery, "", "", $error);
    }

    public  function EditGallery($link, $title, $description, $error = null)
    {
        $content = file_get_contents('..\view\html\area\newGallery.html');

        $content = str_replace("<!--link-->", "/".$link, $content);
        $content = str_replace("<!--TITLE-VALUE-->", $title, $content);
        $content = str_replace("<!--CONTENT-VALUE-->", $description, $content);
        $content .= "<h2>" . $error . "</h2>";

        return $content;
    }

    public function DisplayGallery($gallery_id)
    {


        $gallery = Repository::gallery()->getGalleryById($gallery_id);



        $content = "";
        if (!($gallery instanceof GalleryModel)) throw new Exception("Own Exception: inctance of failed");
        $user = Repository::user()->getUserById($gallery->user_id);


        if (!($user instanceof UserModel)) throw new Exception("Own Exception: inctance of failed");

        $content .= "<h1>" .  $gallery->name . "</h1>";


        //TODO: Change in username
        //TODO: Attention: Two <br><br>s
        $content .= "<a href='\\". Singleton::getUrlSegments()->User . "\\" .  $gallery->user_id ."'><h2>" .  $user->email . "</h2></a><br><br>";


        if ($gallery->user_id == GlobalVariables::GetSessionId())
        {
            $content .=  View::getLinkBox(Singleton::getUrl()->deleteGallery($gallery_id), "Edit Gallery");
        }


        if ($gallery->user_id == GlobalVariables::GetSessionId())
        {
            $content .=  View::getLinkBox(Singleton::getUrl()->deleteGallery($gallery_id), "Delete Gallery");
        }

        if ($gallery->user_id == GlobalVariables::GetSessionId())
        {
            $content .=  View::getLinkBox(Singleton::getUrl()->addImage($gallery_id), "Add Image");
        }

        foreach (Repository::picture()->getAllPicturesByGalleryId($gallery_id) as $image)
        {
            if (!($image instanceof ImageModel)) throw new Exception("Own Exception: inctance of failed");

            $content .= View::picture()->DisplayImageAsBox($image);

        }

        return $content;
    }

    public function AddGallery($galleryId)
    {
        $content = file_get_contents('..\view\html\area\addPicture.html');

        $link = Singleton::getUrl()->addImage($galleryId);

        $content = str_replace("<!--LINK-->", "/".$link, $content);
        return $content;
    }

    public function DeleteGallery($gallery_id)
    {

        $fileName = file_get_contents('..\view\html\area\deleteUser.html');

        $file = str_replace("<!--login-->", "action='\\" . Singleton::getUrl()->deleteGallery($gallery_id) . "'", $fileName);

        return $file;


    }

}