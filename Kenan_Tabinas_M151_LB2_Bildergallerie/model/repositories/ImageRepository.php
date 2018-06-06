<?php
/**
 * Created by PhpStorm.
 * User: vmadmin
 * Date: 03.05.2018
 * Time: 14:57
 */

class ImageRepository extends MainRepository
{
    /**
     * @param $picture ImageModel
     * @throws Exception
     */
    public function addPicture($picture)
    {
        $query = "insert into picture (path, gallery_id, path_thumpnail) VALUES (?, ?, ?)";
        $binds = array($picture->path, $picture->gallery_id, $picture->path_thumbnail);
        $this->execute($query,  $binds, 'sss');
    }


    private function executeQuery($query, $binds = null, $questionMarks = null)
    {


        $stmt = $this->execute($query, $binds, $questionMarks);

        $stmt->bind_result($path, $gallery_id, $thumbnail);

        $pictures = array();

        while ($stmt->fetch())
        {
            $picture = new ImageModel($path, $gallery_id, $thumbnail);
            array_push($pictures, $picture);
        }

        return $pictures;
    }

    public function getAllPicturesByGalleryId($id)
    {
        $query = "SELECT * FROM picture where gallery_id = ?";
        return $this->executeQuery($query, $id, "i");
    }

    public function deleteImagesFromGallery($gallery_id)
    {
        $query = "Delete from picture where gallery_id = ?";
        return $this->execute($query, $gallery_id, "i");
    }



}