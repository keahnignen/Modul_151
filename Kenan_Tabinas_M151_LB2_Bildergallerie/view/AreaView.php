<?php
/**
 * Created by PhpStorm.
 * UserView: Keahnignen
 * Date: 25/11/2017
 * Time: 18:06
 */

/**
 * OLD
 */



class AreaView extends MainView
{

    public function makeContent()
    {



        $controller = new UserController();

        if (isset(self::$uriFragments[1]))
        {

            switch (self::$uriFragments[1])
            {
                case 'login':

                    if (isset($_POST['password_login']) && isset($_POST['email_login']))
                    {
                        if ($controller->tryToLogin($_POST['email_login'], $_POST['password_login']))
                        {
                            $repo = new UserRepository();
                            $_SESSION['id'] = $repo->getIdByEmail($_POST['email_login']);
                            header('Location: /area');
                            exit();
                        }
                        else
                        {
                            $this->displayError('Password or Email is wrong');
                        }
                    }

                    break;

                case 'logout':
                    if (isset($_SESSION['id'])) session_destroy();
                    exit();

                case 'posts':
                    $this->displayManagePosts();
                    return;

                default:
                    break;
            }
        }

        if (isset($_SESSION['id']))
        {
            $this->userArea();
            return;
        }

        $this->displayLoginOrRegister();
        return;
    }


    private function displayError($error)
    {
        self::$content .= '<h1 class="">'. $error . '</h1>';
    }

    private function displayLoginOrRegister()
    {
        self::$content .= file_get_contents('..\view\html\login.html');
    }


    private function displayManagePosts()
    {
        if (self::$queryStrings == null)
        {

            self::$content .= file_get_contents('..\view\html\area\posts.html');
            require_once '..\view\PostView.php';
            $view = new PostView();
            $view->addEditablePosts();
        }
        else {

            $index = null;

            $delete = "delete";

            $edit = "edit";

            $update = "update";

            $newPost = "newPost";

            $addPost = "addPost";


            var_dump("asdasd");

            if (array_key_exists($delete, self::$queryStrings))
            {
                $index = $delete;
            }

            if (array_key_exists($edit, self::$queryStrings))
            {
                $index = $edit;
            }


            if (array_key_exists($update, self::$queryStrings))
            {
                $index = $update;
            }

            if (array_key_exists($newPost, self::$queryStrings))
            {
                $index = $newPost;
            }

            if (array_key_exists($addPost, self::$queryStrings))
            {
                $index = $addPost;
            }

            if ($index != null)
            {
                if (isset(self::$queryStrings[$index]))
                {

                    if (is_numeric(self::$queryStrings[$index]))
                    {

                        $controller = new PostController();

                        switch ($index)
                        {
                            case $delete:
                                $controller->deletePost(self::$queryStrings[$index]);
                                break;
                            case $edit:
                                $controller->editPost(self::$queryStrings[$index]);
                                break;
                            case $update:
                                $controller->updatePost(self::$queryStrings[$index]);
                                break;
                            case $newPost:
                                $controller->newPost();
                                break;
                            case $addPost:
                                $controller->addPost();
                                break;
                        }
                    }
                }
            }


        }


    }


    public function userArea()
    {
        $u = new UserRepository();
        self::$headerText = ' Logout';
        self::$href = ' href="/area/logout" ';
        $html = file_get_contents('..\view\html\area.html');
        self::$content .= str_replace('<!--Username-->', $u->getUsernameById($_SESSION['id'][0]), $html);
    }

}