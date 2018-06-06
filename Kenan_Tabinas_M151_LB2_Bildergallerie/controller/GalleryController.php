<?php
/**
 * Created by PhpStorm.
 * User: Keahnignen
 * Date: 27/05/2018
 * Time: 21:20
 */

class GalleryController
{

    public function CreateNewGallery()
    {
        //TODO: Check values

        $name = GlobalVariables::getPost("title");
        $description = GlobalVariables::getPost("description");

        if ($name == null || $description == null)
        {
            return "Not all Fields were filled";
        }

        Repository::gallery()->addGallery(new GalleryModel(null, $name, $description, GlobalVariables::GetSessionId()));
        return true;
    }

    public function DisplayGallery()
    {
        $id = GlobalVariables::getUriFragments(1);

        if (Repository::gallery()->doesGalleryExist($id))
        {
            if (GlobalVariables::getUriFragments(2) == Singleton::getUrlSegments()->addImage)
            {
                $index = "file";


                if (!array_key_exists($index, $_FILES))
                {
                    return View::gallery()->AddGallery($id);
                }

                Controller::picture()->AddPicture($index, $id);

            }

            return View::gallery()->DisplayGallery($id);
        }
        return View::ErrorMessage("Error 404 - We didn't found your page");
    }

    public function DeleteGallery()
    {


        $gallery_id = GlobalVariables::getUriFragments(2);

        if (!Repository::gallery()->doesGalleryExist($gallery_id)) return "No rights to do this";


        if (isset($_POST['password']) && isset($_POST["email"]))
        {

            if (Controller::user()->isPasswordCorrect($_POST['email'], $_POST['password']))
            {

                $user_id = GlobalVariables::GetSessionId();
                Repository::gallery()->deleteGallery($gallery_id, $user_id);

                Dispatcher::moveTo(Singleton::getUrlSegments()->userArea);


            }
            return View::gallery()->DeleteGallery($gallery_id);
        }
        else {

            return View::gallery()->DeleteGallery($gallery_id);
        }

    }


}