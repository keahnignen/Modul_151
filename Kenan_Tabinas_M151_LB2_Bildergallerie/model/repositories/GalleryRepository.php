<?php
/**
 * Created by PhpStorm.
 * User: vmadmin
 * Date: 03.05.2018
 * Time: 14:57
 */

class GalleryRepository extends MainRepository
{
    /**
     * @param $Gallery GalleryModel
     * @throws Exception
     */
    public function addGallery($Gallery)
    {
        var_dump($Gallery);
        $query = "insert into Gallery (name, description, user_id) VALUES (?, ?, ?)";
        $binds = array($Gallery->name, $Gallery->description, $Gallery->user_id);
        $this->execute($query,  $binds, 'ssi');
    }


    private function executeQuery($query, $binds = null, $questionMarks = null)
    {


        $stmt = $this->execute($query, $binds, $questionMarks);

        $stmt->bind_result($id, $name, $description, $user_id);

        $galleries = array();

        while ($stmt->fetch())
        {
            $galleryModel = new GalleryModel($id, $name, $description, $user_id);
            array_push($galleries, $galleryModel);
        }

        return $galleries;
    }

    public function getAllGalleries()
    {
        $query = "SELECT * FROM gallery";
        return $this->executeQuery($query);
    }

    public function getAllGalleriesByUserId($id)
    {
        $query = "SELECT * FROM gallery where user_id = ?";
        return $this->executeQuery($query, $id, "i");
    }

    public function getGalleryById($id)
    {
        $query = "SELECT * FROM gallery where id = ?";
        return $this->executeQuery($query, $id, "i")[0];
    }

    public function doesGalleryExist($id)
    {
        $query = "SELECT id FROM gallery where id = ?";
        return $this->executeIsNotNull($query, $id, "i");
    }

    public function deleteGallery($id, $user_id)
    {
        Repository::picture()->deleteImagesFromGallery($id);
        $query = "DELETE FROM gallery where id = ? and user_id = ?";
        return $this->execute($query, array($id, $user_id), "ii");
    }


}