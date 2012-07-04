<?php
/*
	bounce Framework - Database controller, create connector based up configuration
	
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

  class Database
  {
    private static $_instance;
    private $_connection;
    private $_configuration;    
  
    private function __construct()
    {
      try 
	  {
		$this->_configuration = Configuration::GetInstance( );

		if( $this->_configuration)
		{
            $siteprofile = $this->_configuration['configuration']['siteprofile'];

            $databaseConnector = $this->_configuration[ $siteprofile][ "database" ];
            $connector = $this->_configuration[ $databaseConnector ][ "connector"];

		    $databaseClass = new ReflectionClass( $connector);
		  
		    if( $databaseClass->isInstantiable( ))
		    {
		        $this->_connection = $databaseClass->newInstance( );
		    
    		    $this->_connection->Connect( $this->_configuration[ $databaseConnector][ "username"],
		                                  $this->_configuration[ $databaseConnector][ "password"],
		                                  $this->_configuration[ $databaseConnector][ "host"],
		                                  $this->_configuration[ $databaseConnector][ "database"]);
            }
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
    public function &Connection( )
    {
        if( !isset( self::$_instance))
        {
            $c = __CLASS__;
            self::$_instance = new $c( );
        }
        
        return self::$_instance->_connection;
    }
  }

?>