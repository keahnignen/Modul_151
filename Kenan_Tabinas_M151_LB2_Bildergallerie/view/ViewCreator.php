<?php
/**
 * Created by PhpStorm.
 * User: Keahnignen
 * Date: 23/03/2018
 * Time: 21:38
 */

class ViewCreator
{

    private static function getLayoutWithCss()
    {

        $layout = file_get_contents('..\view\html\layout.html');

        $uri = $_SERVER['REQUEST_URI'];
        $uriFragments = explode('/', $uri);

        $begin = '<link href="';

        foreach ($uriFragments as $uriFragment)
        {
            $begin = $begin . '..\\';
        }

        $fullCssString = $begin . 'style.css" type="text/css" rel="stylesheet">';

        $layout = str_replace('<!--CSS-->', $fullCssString, $layout);

        return $layout;
    }

    public static function displayPage($content)
    {
        $content = Navbar::getNavbar() . $content . '</div>';
        echo str_replace('<!--THIS_WILL_BE_REPLACED-->', $content, self::getLayoutWithCss());
    }


}