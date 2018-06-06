<?php
/**
 * Created by PhpStorm.
 * UserView: Keahnignen
 * Date: 18/11/2017
 * Time: 15:17
 */


class MainRepository
{
    protected $mysqli;

    public function __construct()
    {

        $this->mysqli = new mysqli('localhost', 'root', '', 'images');


        if ($this->mysqli->connect_error)
        {
            throw new Exception("Metalica");
        }



        if (mysqli_connect_errno())
        {
            throw new Exception("mysqli_connect_errno");
        }

    }

    /**
     * @param $query string only one return type
     * @param null $binds
     * @param null $questionMarks
     * @return bool
     */

    protected function executeIsNotNull($query, $binds = null, $questionMarks = null)
    {
        $stmt = $this->execute($query, $binds, $questionMarks);
        $stmt->bind_result($bla);
        $stmt->fetch();

        return $bla != null;
    }


    /**
     * @param $query
     * @param null $binds
     * @param null $questionMarks
     * @return mysqli_stmt
     * @throws Exception
     */



    protected function execute($query, $binds = null, $questionMarks = null)
    {

        $stmt = $this->mysqli->prepare($query);


        var_dump($query);


        if ($stmt == false) throw new Exception("Db prepare error");

        if ($binds != null && $questionMarks != null)
        {

            if (!is_array($binds))
            {
                $binds = array($binds);
            }

            $parameters = array();

            array_push($parameters, $questionMarks);

            foreach ($binds as $bind)
            {
                array_push($parameters, $bind);
            }

            call_user_func_array(array($stmt, 'bind_param'), $this->getRefValue($parameters));

            //$stmt->bind_param($questionMarks, $binds);
        }

        if (!$stmt->execute()) throw new Exception("Own Exception: Execution error - Throwed Exception");

        return $stmt;
    }



    private function getRefValue($arr)
    {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }

    protected function getOneColumn($query, $binds = null, $questionMarks = null)
    {
        $stmt = $this->execute($query, $binds, $questionMarks);
        $stmt->bind_result($column);

        $columns = array();

        while ($stmt->fetch())
        {
            array_push($columns, $column);
        }
        return $columns;
    }


}