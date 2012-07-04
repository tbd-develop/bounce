<?php
/**
 * Created by JetBrains PhpStorm.
 * User: terry
 * Date: 7/4/12
 * Time: 8:20 AM
 * To change this template use File | Settings | File Templates.
 */
class DummyConnector implements IDatabaseConnection
{
    public function Connect($username = "", $password = "", $host = "", $database = "")
    {
        return;
    }

    public function ExecuteQuery()
    {
        return new ResultSet(array());
    }

    public function LastInsertId()
    {
        return 0;
    }

    public function __get($name)
    {
        return;
    }
}
