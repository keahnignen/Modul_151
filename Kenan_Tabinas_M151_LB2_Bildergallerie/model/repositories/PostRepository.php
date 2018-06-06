<?php
/**
 * Created by PhpStorm.
 * UserView: Keahnignen
 * Date: 18/11/2017
 * Time: 23:52
 */

class PostRepository extends MainRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllPosts()
    {
        $query = "SELECT * FROM post";
        $variable = $this->executeStatement($query);
        return $variable;
    }



    /**
     * @param $query string
     * @param $binds string
     * @param $questionMarks string
     * @return array
     * @throws Exception
     */

    private function executeStatement($query, $binds = null, $questionMarks = null)
    {
        $stmt = $this->execute($query, $binds, $questionMarks);

        $stmt->bind_result($id, $text, $topic_id, $user_id, $title, $date);

        $users = array();

        while ($stmt->fetch())
        {
            $postModel = new PostModel();
            $postModel->id = $id;
            $postModel->text = $text;
            $postModel->topic_id = $topic_id;
            $postModel->user_id = $user_id;
            $postModel->date = $date;
            $postModel->title = $title;
            array_push($users, $postModel);
        }

        return $users;
    }


    public function getAllPostByUser($id)
    {
        $query = "SELECT * FROM post where user_id = ? ORDER BY post.date DESC";
        return $this->executeStatement($query, $id, 's');
    }

    public function getPostById($id)
    {
        $query = "SELECT * FROM post where id = ?";
        return $this->executeStatement($query, $id, 's');
    }

    public function deletePost($id)
    {
        $binds = array($id);
        $query = "DELETE FROM post WHERE id = ?";
        $this->execute($query, $binds, 'i');
        return;
    }

    public function updatePost($post)
    {
        $binds = array($post->title, $post->text, intval($post->id));
        $query = "UPDATE   post SET title=?, text=? WHERE id = ?";
        $this->execute($query, $binds, 'ssi');
        return;
    }

    public function newPost($post)
    {
        $binds = array($post->title, $post->text);
        $query = "INSERT INTO post (text, user_id, topic_id, title) VALUES (?, {$_SESSION["id"][0]}, 1, ?)";
        $this->execute($query, $binds, 'ss');
        return;
    }


}