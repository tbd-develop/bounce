<?php
/*
	Bounce Framework - Database controller, create connector based up configuration
	
    Copyright (C) 2013  Terry Burns-Dyson

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

  class Database
  {
    private static $_instance;
    private static $_connection;    
    private $_configuration;    
  
    private function __construct( )
    {
      try 
  	  {
        $profile = $this->_configuration = Configuration::GetProfile( );

        if( $profile)
        {
            if( Configuration::HasProperty('database')) {
                $database = $profile->database;

                $databaseClass = new ReflectionClass( "{$database->connector}");

                if( $databaseClass->isInstantiable( ))
                {
                  self::$_connection = $databaseClass->newInstance( );

                  self::$_connection->Connect( $database->username, $database->password,$database->host,$database->name);
                }
            } else
                self::$_connection = new DummyConnector();
        }
      }
  	  catch( Exception $error)
        {
          throw new DatabaseException( "Error in database");
        }
    }    
    
	/*
	 * 
	 * @return IDatabaseConnection
	 */
    public static function &Connection( )
    {
         if( !isset( self::$_instance))
            self::$_instance = new Database( );
        
      return self::$_connection; 
    }
  }

?>