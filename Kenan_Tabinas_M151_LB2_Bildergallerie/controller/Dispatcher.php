<?php
/**
 * Created by PhpStorm.
 * User: Keahnignen
 * Date: 23/03/2018
 * Time: 22:01
 */

class Dispatcher
{

    public static function dispatch() {

        $s = new GlobalVariables();
        $content ="";

        switch (GlobalVariables::getUriFragments(0))
        {

            case Singleton::getUrl()->UserArea:
                $content = self::Userarea();
                break;
            case Singleton::getUrl()->Register:
                $content = UserController::CreateUser();
                break;
            case Singleton::getUrl()->Login:
                $content = UserController::Login();
                break;
            case Singleton::getUrl()->Logout:
                UserController::Logout();
                break;
            case Singleton::getUrl()->ShowGallery:
                $content = Controller::gallery()->DisplayGallery();
                break;

            case Singleton::getUrlSegments()->image;
                View::picture()->DisplayImage();

                break;
            default:
                $content = Homepage::Display();
        }

        ViewCreator::displayPage($content);
    }

    public static function moveTo($where)
    {
        header('Location: /' . $where);
    }

    public static function Userarea()
    {
        if (GlobalVariables::$IsSessionIdSet)
        {
            if (GlobalVariables::getUriFragments(1) == Singleton::getUrlSegments()->NewGallery)
            {
                return View::gallery()->CreateNewGallery();
            }


            if (GlobalVariables::getUriFragments(1) == Singleton::getUrlSegments()->deleteUser)
            {
                return Controller::user()->deleteUser();
            }

            if (GlobalVariables::getUriFragments(1) == Singleton::getUrlSegments()->deleteGallery)
            {
                return Controller::gallery()->DeleteGallery();
            }

            if (GlobalVariables::getUriFragments(1) == Singleton::getUrlSegments()->saveGallery)
            {
                //Check if login data was valid
                $x = Controller::gallery()->CreateNewGallery();
                if (!($x === true))
                {
                    //TODO: Display current values
                    //Display error message
                    return View::gallery()->CreateNewGallery($x);
                }
                Dispatcher::moveTo(Singleton::getUrl()->UserArea);
            }

            return View::user()->DisplayLoggedIn();
        }

        return View::user()->Login();
    }

}