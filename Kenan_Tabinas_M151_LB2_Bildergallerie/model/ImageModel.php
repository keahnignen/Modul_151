<?php
/**
 * Created by PhpStorm.
 * User: vmadmin
 * Date: 03.05.2018
 * Time: 15:00
 */

class ImageModel
{

    /**
     * @var int
     */
    public $path;

    /**
     * @var string
     */
    public $gallery_id;

    /**
     * @var int
     */

    public $path_thumbnail;

    public function __construct($path, $gallery_id, $thumbnail)
    {
        $this->path = $path;
        $this->gallery_id = $gallery_id;
        $this->path_thumbnail = $thumbnail;
    }


}