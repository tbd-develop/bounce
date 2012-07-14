<?php
/*
	bounce Framework - Base include file for configuration and autoload
	
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
    session_start( );

    include_once( 'library/autoloader.php');
    include_once('library/configuration/simpleconfiguration.php');
    include_once( 'library/overrides.php');

    $autoloader = new AutoLoader();

    define( 'DIRSEP', DIRECTORY_SEPARATOR);
    define( 'HTTP_ROOT', "http://" . $_SERVER[ 'HTTP_HOST'] );

    // Load the configuration script
    SimpleConfiguration::Load( realpath( dirname( __FILE__ ) . DIRSEP . "configuration.xml" ));

    // Get an instance to use immediately
	$configuration = SimpleConfiguration::GetInstance( );

    $siteprofile = $configuration->GetSetting('defaults', 'siteprofile', 'default');

	$rootPath = trim( realpath( dirname( __FILE__) . '..' . DIRSEP . '..' ));

    $rootPath = !empty( $rootPath) ? realpath( dirname( __FILE__) . '..' . DIRSEP . '..' ) :
				$configuration->GetSetting( $siteprofile, 'rootpath');

    define( 'ROOT_PATH', $rootPath);

	try
  	{
          $database = Database::Connection( );
  	}
  	catch(DatabaseException $dbError)
  	{
  		if( $configuration->GetSetting('debug', 'enabled'))
			echo "Database not configured";
  	}

 	// RouteRegistration is loaded per session (PHP doesn't have application level vars)
	if( isset($_REQUEST["reload"])) {
		$_SESSION["RouteRegister"] = null;
	}
  
    RouteRegister::GetInstance();
?>
