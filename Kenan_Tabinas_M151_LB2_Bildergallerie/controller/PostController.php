<?php
/**
 * Created by PhpStorm.
 * User: Keahnignen
 * Date: 16/12/2017
 * Time: 11:41
 */

class PostController
{



    public function __construct()
    {
        $this->repo = new PostRepository();
    }

    private $repo;

    function addPost()
    {
        $post = new PostModel();
        $post->title = $_POST["title"];
        $post->text = $_POST["content"];
        $this->repo->newPost($post);
        header("Location: /area/posts");
    }



    public function deletePost($id)
    {
        $Confirmation = null;
        $Confirmation = "<script> window.confirm('Are you sure to delete this post?');
        </script>";
        echo $Confirmation;

        if ($Confirmation == true) {
            $this->repo->deletePost($id);

        }

        header("Location: ".Singleton::getUrl()->UserArea);

    }

    function editPost($id)
    {
        $user = new PostView();
        $user->addEditPost($id);
    }

    function newPost()
    {
        $user = new PostView();
        $user->addEditPost();
    }

    function updatePost($id)
    {
        $post = new PostModel();
        $post->id = $id;
        $post->title = $_POST["title"];
        $post->text = $_POST["content"];

        $this->repo->updatePost($post);

        header("Location: ".Singleton::getUrl()->UserArea);
    }

}