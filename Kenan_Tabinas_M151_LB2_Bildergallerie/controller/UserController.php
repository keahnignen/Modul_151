<?php
/**
 * Created by PhpStorm.
 * User: Keahnignen
 * Date: 26/11/2017
 * Time: 15:13
 */

class UserController
{

    public static function tryToLogin($email, $password)
    {
        $x = self::isPasswordCorrect($email, $password);

        if (!is_bool($x)) return $x;

        if ($x) {
            GlobalVariables::SetSessionId(Repository::user()->getIdByEmail($email));
            header('Location: /' . Singleton::getUrl()->UserArea);
            exit();
        }
        else {
            return "Password is wrong";
        }

    }

    public static function isPasswordCorrect($email, $password)
    {


        $password_hashed = Repository::user()->getPassword($email);


        if ($password_hashed == null)
        {
            return "Email is not registert";
        }


        $size = sizeof($password_hashed);

        if ($size != 1)
        {

            throw new Exception("This email is registert to more than one user. We are sorry. ");
        }


        return password_verify($password, $password_hashed[0]);
    }


    public static function tryCreateUser($email, $password)
    {


        $s = self::isPasswordValid($password);

        if (!$s)
        {
            return "Password must have a Special Character, an upper- and a lowercase letter, a number and must have 8 characters";
        }

        $ur = new UserRepository();
        if ($ur->isEmailTaken($email))
        {
            return "Email is already Taken!";
        }

        $bla = password_hash($password, PASSWORD_DEFAULT);

        $ur->addUser($email, $bla);

        $id = $ur->getIdByEmail($email);

        GlobalVariables::SetSessionId($id);

        Dispatcher::moveTo(Singleton::getUrl()->UserArea);

        return self::$Registiert;

    }
    
    private static $Registiert = "Registerd";

    private static function isPasswordValid($password)
    {
        $regex = "(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_])(?=.{8,})";
        return preg_match("/". $regex . "/", $password);
    }

    private static function isEmailValid($email)
    {
        $regex = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/";
        return preg_match($regex, $email);
    }



    public static function BlaUser()
    {
        if (!self::isEmailValid($_POST['email'])) {
            return "Email is not valid";
        }
        if ($_POST['password'] !== $_POST['control_password'])
        {
            return "Password are unidenical";

        }
        return self::tryCreateUser($_POST['email'], $_POST['password']);
    }

    public static function CreateUser()
    {



        $b = self::BlaUser();

        $asd = Area::Display();

        $asd .=   '<div class="floatClear"></div><div class="normalMessage"><h1>' . $b . "</h1></div>";
        return $asd;
    }

    public static function Login()
    {

        if (!isset($_POST['email_login']) || !isset($_POST["password_login"])) Dispatcher::moveTo(Singleton::getUrl()->UserArea);


        $b = self::tryToLogin($_POST['email_login'], $_POST['password_login']);


        $asd = View::user()->Login();
        $asd .=   '<div class="floatClear"></div><div class="normalMessage"><h1>' . $b . "</h1></div>";
        return $asd;
    }

    public static function Logout()
    {
        GlobalVariables::SetSessionId(0);
        session_destroy();
        Dispatcher::moveTo("");
        die();
    }

    public function deleteUser()
    {

        $user_id = GlobalVariables::GetSessionId();

        if (isset($_POST['password']) && isset($_POST["email"]))
        {

            if (self::isPasswordCorrect($_POST['email'], $_POST['password']))
            {

                Repository::user()->deleteUserId($user_id);
                Dispatcher::moveTo(Singleton::getUrl()->Logout);


            }
            return View::user()->DeleteUser();
        }
        else {
            return View::user()->DeleteUser();
        }


    }


}