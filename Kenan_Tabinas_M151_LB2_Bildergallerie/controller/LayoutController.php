<?php
/**
 * Created by PhpStorm.
 * User: Keahnignen
 * Date: 23/03/2018
 * Time: 23:36
 */

class LayoutController
{
    private static function getLayoutWithoutContent()
    {

        $layoutWithoutCss = file_get_contents('..\view\html\layout.html');

        $layout = str_replace('<!--CSS-->', self::getDynamicCssLocation(), $layoutWithoutCss);

        return $layout;
    }

    private static function getDynamicCssLocation()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uriFragments = explode('/', $uri);

        $begin = '<link href="';

        foreach ($uriFragments as $uriFragment)
        {
            $begin = $begin . '..\\';
        }

        return $begin . 'style.css" type="text/css" rel="stylesheet">';

    }

    private static function getNavbar()
    {
        $header = file_get_contents('..\view\html\header.html');
        $newHeader = str_replace('<!--Header-->', self::$headerText, $header);
        return str_replace('<!--href-->', self::$href, $newHeader);
    }

    private static function getNavbarButtonText()
    {
        if (GlobalVariables::$IsSessionIdSet)
            return "UserArea";
        return "UserArea";
    }

}