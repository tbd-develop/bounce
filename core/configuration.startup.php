<?php
/*
    Bounce Framework - Base include file for configuration and autoload

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

    session_start( );

    include_once( 'library/autoloader.php');
    include_once('library/configuration/configuration.php');
    include_once('library/configuration/jsonconfiguration.php');
    include_once( 'library/overrides.php');

    $autoloader = new AutoLoader();

    define( 'DIRSEP', DIRECTORY_SEPARATOR);
    define( 'HTTP_ROOT', "http://" . $_SERVER[ 'HTTP_HOST'] );

    $configurationFilePath = realpath( dirname( "." )) . DIRSEP . "core/configuration.json";

    JsonConfiguration::Load( $configurationFilePath);

    $site = Configuration::GetInstance();
    $profile = Configuration::GetProfile();

    $rootPath = trim( realpath( dirname( ".")));

    $rootPath = !empty( $rootPath) ? $rootPath : $profile->rootpath;

    define( 'ROOT_PATH', $rootPath);

    try
    {
        if( Configuration::HasProperty('database'))
            $database = Database::Connection( );
    }
    catch(DatabaseException $dbError)
    {
        if( $site->debug)
            echo "Database not configured";
    }

    if( isset($_REQUEST["reload"])) {
        $_SESSION["RouteRegister"] = null;
    }

    RouteRegister::GetInstance();

    include_once('configuration.custom.php');
?>
