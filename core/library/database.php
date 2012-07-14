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

class DatabaseSettings
{
    public $Connector;
    public $UserName;
    public $Password;
    public $Host;
    public $Database;
}

class Database
  {
    private static $_instance;
    private $_connection;
    private $_configuration;    
  
    private function __construct()
    {
      try 
	  {
        $settings = $this->getDatabaseSettings();

        $databaseClass = new ReflectionClass( (string)$settings->Connector);

        if( $databaseClass->isInstantiable( ))
        {
            $this->_connection = $databaseClass->newInstance( );

            $this->_connection->Connect( $settings->UserName, $settings->Password, $settings->Host, $settings->Database);
        }
      }
	  catch( Exception $error)
      {
        throw new DatabaseException( "Error in database");
      }
    }

    private function getDatabaseSettings()
    {
      $this->_configuration = SimpleConfiguration::GetInstance( );

      if( $this->_configuration)
      {
          $settings = new DatabaseSettings();

          $siteprofile = $this->_configuration->GetSetting('defaults', 'siteprofile', 'default');

          $databaseSettings = $this->_configuration->GetSetting($siteprofile, 'database');

          $databaseProfile = $this->_configuration->GetSettingsCollection($databaseSettings);

          $settings->Connector = $databaseProfile['connector'];
          $settings->UserName = $databaseProfile['username'];
          $settings->Password = $databaseProfile['password'];
          $settings->Host = $databaseProfile['host'];
          $settings->Database = $databaseProfile['database'];
      }

      return $settings;
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