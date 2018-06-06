<?php
/**
 * Created by PhpStorm.
 * User: Keahnignen
 * Date: 25/05/2018
 * Time: 23:04
 */

class Singleton
{

    private static $url;
    private static $urlSegments;



    /**
     * @return URL
     */
    public static function getUrl()
    {
        if ( is_null( self::$url ) )
        {
            self::$url = new URL();
        }
        return self::$url;
    }

    public static function getUrlSegments(){
        if ( is_null( self::$urlSegments ) )
        {
            self::$urlSegments = new URLFragments();
        }
        return self::$urlSegments;
    }






}