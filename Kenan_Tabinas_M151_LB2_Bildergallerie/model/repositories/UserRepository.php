<?php
/**
 * Created by PhpStorm.
 * UserView: Keahnignen
 * Date: 18/11/2017
 * Time: 14:53
 */

class UserRepository extends MainRepository
{

    public function __construct()
    {
        parent::__construct();
    }


    private function executeStatement($query, $binds = null, $questionMarks = null)
    {
        $stmt = $this->execute($query, $binds, $questionMarks);

        $stmt->bind_result($id, $username, $email, $password, $isAdmin);

        $users = array();

        while ($stmt->fetch())
        {
            $userModel = new UserModel();
            $userModel->id = $id;
            $userModel->username = $username;
            $userModel->email = $email;
            $userModel->password = $password;
            $userModel->isAdmin = $isAdmin;
            array_push($users, $userModel);
        }

        return $users;
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM user";

        $stmt = $this->execute($query);

        $stmt->bind_result($id, $username, $email, $password, $isAdmin);

        $users = array();

        while ($stmt->fetch())
        {
            $userModel = new UserModel();
            $userModel->id = $id;
            $userModel->username = $username;
            $userModel->email = $email;
            $userModel->password = $password;
            $userModel->isAdmin = $isAdmin;
            array_push($users, $userModel);
        }

        return $users;
    }


    public function isUsernameTaken()
    {
        return false;
    }

    public function isEmailTaken($email)
    {
        $query = "select email from user where email = ?";
        $stmt = $this->execute($query, $email, 's');
        $stmt->bind_result($bla);
        $stmt->fetch();

        return $bla != null;
    }

    public function getUsernameById($id)
    {
        return $this->getEmailById($id);
    }

    public function getUserByUsername()
    {

    }

    public function getAllEmails()
    {
        $query = "SELECT email FROM `user`";
        return $this->getOneColumn($query);
    }

    public function getAllUsernames()
    {
        return $this->getAllEmails();
    }

    public function getEmailById($id)
    {
        $query = "SELECT email FROM `user` WHERE id = ?";
        $stmt = $this->execute($query, $id, 's');
        $stmt->bind_result($email);
        $stmt->fetch();
        return $email;
    }

    public function getIdByEmail($email)
    {
        $query = "SELECT id FROM user where email = ?";
        return $this->getOneColumn($query, $email, 's')[0];
    }

    public function addUser($email, $password)
    {
        $query = "insert into user (username, email, PASSWORD, isAdmin) VALUES (?, ?, ?, FALSE)";
        $binds = array("", $email, $password);
        $this->execute($query,  $binds, 'sss');
    }

    public function getPassword($email)
    {
        $query = "select password from user where email = ?";
        $binds = array($email);
        return $this->getOneColumn($query, $binds, 's');
    }

    public function getUserById($id)
    {
        $query = "select * from user where id = ?";
        return $this->executeStatement($query, $id, 'i')[0];
    }

    public function deleteUserId($id)
    {
        foreach (Repository::gallery()->getAllGalleriesByUserId($id) as $galleries)
        {
            Repository::gallery()->deleteGallery($galleries->id, $id);
        }

        $query = "delete from user where id = ?";
        return $this->execute($query, $id, 'i');
    }

}