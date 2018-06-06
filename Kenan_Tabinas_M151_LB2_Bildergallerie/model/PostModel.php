<?php
/**
 * Created by PhpStorm.
 * UserView: Keahnignen
 * Date: 18/11/2017
 * Time: 23:50
 */

class PostModel
{

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $text;

    /**
     * @var int
     */

    public $user_id;

    /**
     * @var int
     */

    public $topic_id;

    /**
     * @var \MongoDB\BSON\Timestamp
     */

    public $date;

    /**
     * @var string
     */

    public $title;
}