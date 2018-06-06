<?php
/**
 * Created by PhpStorm.
 * User: vmadmin
 * Date: 03.05.2018
 * Time: 15:00
 */

class GalleryModel
{

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */

    public $description;


    public $user_id;





    public function __construct($id_nullIsOK = null, $name, $description, $user_id)
    {
        $this->id = $id_nullIsOK;
        $this->name = $name;
        $this->description = $description;
        $this->user_id = $user_id;

    }


    public function newGallery($Gallery)
    {

    }


}