<?php
/**
 * Created by PhpStorm.
 * User: Keahnignen
 * Date: 23/03/2018
 * Time: 21:47
 *
 */

class GlobalVariables
{

    public static $ImagePath = "..\\..\\..\\Images\\";

    public static $ApplicationName = "Images[]";

    public static $HtmlContent = "<!--THIS_WILL_BE_REPLACED-->";

    private static $idIndex = "id";

    private static $uriFragments;

    public static $queryStrings;


    public static function getUriFragments($index)
    {
        if (isset(self::$uriFragments[$index]))
        {
            return self::$uriFragments[$index];
        }
        return null;
    }


    public function getPost($name)
    {
        if (!isset($_POST[$name])){
            return null;
        }
        return $_POST[$name];
    }


    public function __construct()
    {
        self::$IsSessionIdSet = (isset($_SESSION[self::$idIndex]) && $_SESSION[self::$idIndex] != null);
        self::initQueryString();
        self::initUriFragments();
    }

    public static $IsSessionIdSet;


    public static function GetSessionId()
    {

        return $_SESSION[self::$idIndex];
    }

    public static function SetSessionId($id)
    {
        $_SESSION[self::$idIndex] = $id;
    }

    private static function initUriFragments()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = strtok($uri, '?');
        $uri = trim($uri, '/');

        $bla = explode('/', $uri);

        self::$uriFragments = $bla;
    }


    private static function initQueryString()
    {
        if (self::$queryStrings == null) {

            self::$queryStrings = array();

            if (!empty($_SERVER["QUERY_STRING"])) {
                foreach (explode('&', $_SERVER["QUERY_STRING"]) as $queryString) {

                    $queryStringFragments = explode('=', $queryString);
                    self::$queryStrings[$queryStringFragments[0]] = $queryStringFragments[1];
                }
            }
        }


    }
}

