<?php
/*
	bounce Framework - MysqliConnector
	
    Copyright (C) 2012  Terry Burns-Dyson

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class MysqliConnector implements IDatabaseConnection
{
    private $_data;
    private $_connection;

    public function __construct()
    {
        $_connection = null;
        $_data = array();
    }

    public function Connect($username = "", $password = "", $host = "", $database = "")
    {
        $this->_connection = new mysqli($host, $username, $password, $database);

        if (!$this->_connection)
            throw new DatabaseException("Failed to connect to MySQL Database {$database}");
    }

    public function ExecuteQuery()
    {
        $result = null;

        if (func_num_args() > 0) {
            $statement = $this->_connection->stmt_init();

            if (!$statement->prepare(func_get_arg(0)))
                throw new DatabaseException("Unable to prepare statement for query");

            if (func_num_args() > 1) {
                $arguments = func_get_args();
                $query = array_shift($arguments);
                $types = "";
                $i = 0;

                foreach ($arguments as $argument)
                {
                    $types .= substr(strtolower(gettype($argument)), 0, 1);

                    $bind_name = 'bind' . $i++;
                    $$bind_name = $argument;

                    $bind_names[] = &$$bind_name;
                }

                $bind_params[] = $types;
                $bind_params = array_merge($bind_params, $bind_names);

                if (!call_user_func_array(array($statement, 'bind_param'), $bind_params)) {
                    throw new DatabaseException("Failed to bind parameters {$statement->error}");
                }
            }

            $statement->execute();

            $statement->store_result();

            if ($statement->num_rows > 0) {
                $data = $statement->result_metadata();

                while ($field = $data->fetch_field())
                {
                    $columnName = str_replace(' ', '_', $field->name);
                    $fields[] = &$results[$columnName];
                }

                call_user_func_array(array($statement, 'bind_result'), $fields);

                while ($statement->fetch())
                {
                    foreach ($results as $key => $value)
                    {
                        $elements[$key] = $value;
                    }

                    $result[] = $elements;
                }
            }

            $statement->free_result();

            $statement->close();

        } else
            throw new DatabaseException("MysqliConnector::ExecuteQuery requires 1 or more parameters");

        return new ResultSet($result);
    }

    public function LastInsertId()
    {
        return mysqli_insert_id($this->_connection);
    }

    public function __get($name)
    {
        if (isset($this->_data[$name]))
            return $this->_data[$name];

        return null;
    }
}

?>