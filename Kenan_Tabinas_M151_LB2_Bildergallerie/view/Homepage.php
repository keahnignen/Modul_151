<?php

/**
 * Created by PhpStorm.
 * User: vmadmin
 * Date: 01.03.2018
 * Time: 14:58
 */

class Homepage
{

    public static function Display()
    {
        return "<h1>". GlobalVariables::$ApplicationName ."</h1><br><h2>Homepage</h2>";
    }

}