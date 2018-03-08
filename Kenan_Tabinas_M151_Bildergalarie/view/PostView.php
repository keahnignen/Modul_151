<?php
/**
 * Created by PhpStorm.
 * UserView: Keahnignen
 * Date: 25/11/2017
 * Time: 18:08
 */

class PostView extends MainView
{

    public function makeContent()
    {

        $this->addNormalPost();
    }

    public function addNormalPost()
    {
        var_dump("sd");
        if (isset(self::$queryStrings["id"])) {
            if (is_numeric(self::$queryStrings["id"])) {
                $repository = new PostRepository();
                self::$content = $this->getPostString($repository->getPostById(self::$queryStrings["id"]), self::$content);
            }
        }
    }

    public function addEditablePosts()
    {
        //var_dump(is_numeric($_SESSION["id"][0]));
        if (isset($_SESSION["id"][0])) {
            if (is_numeric($_SESSION["id"][0])) { //User-Id
                $repository = new PostRepository();
                self::$content = $this->getPostString($repository->getAllPostByUser($_SESSION["id"][0]), self::$content, true);
            }
        }
    }

    public function addEditPost($id = null)
    {
        $html = file_get_contents('..\view\html\area\editPost.html');

        $areaTextContent = "";

        $replace = '?addPost=0';

        if ($id != null)
        {
            //edit existing post

            $p = new PostRepository();

            $post = $p->getPostById($id);

            $post = $post[0];

            $title = " value='" . $post->title . "' >";

            $replace = '?update='.$post->id;

            $html = str_replace('<!--TITLE-VALUE-->', $title, $html);

            $areaTextContent = $post->text;

        }

        $html = str_replace('<!--CONTENT-VALUE-->', $areaTextContent, $html);

        $html = str_replace('<!--TEXT-->', $replace, $html);

        self::$content .= $html;
    }

    public function addComment()
    {

    }
}