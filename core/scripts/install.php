<?php
/*
 bounce Framework - Installation script

 Copyright (C) 2010  Terry Burns-Dyson

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

define( 'MAX_PATH_SIZE', 1024);
define( 'DIRSEP', DIRECTORY_SEPARATOR);
define( 'APPVERSION', '1.0.0.0');

class Install
{
	var $basePath = "";
	var $databaseConnectionName = "";
	var $databaseConnector = "";
	var $databaseName = "";
	var $databaseServer = "";
	var $databaseUser = "";
	var $databasePass = "";
	var $databaseXml = "";
	var $configuration = "";
	var $proceed;
	var $siteTitle = "";
	var $installDb = false;

	var $appDirectory;

	var $invalidDirs = array( "c:/", "c:\\windows/", "c:/windows/", "/");
	var $directories = array(
								"." => array( "dirname" => "."),
								"controllers" => array( "dirname" => "controllers"), 
								"core" => array( "dirname" => "core", 
												 "subdirs" => array( 
												 				"library" => array( "dirname" => "library"),
												 				"connectors" => array( "dirname" => "connectors"),
												 				"exceptions" => array( "dirname" => "exceptions"),
												 				"interfaces" => array( "dirname" => "interfaces"),
																"scripts" => array( "dirname" => "scripts", 
																					"files" => array( "database.php" ) )											 			
	)
	),
								"errors" => array( "dirname" => "errors", 
													"subdirs" => array(
																"styles" => array( "dirname" => "styles") 
	)
	),
								"libraries" => array( "dirname" => "libraries"),
								"templates" => array( "dirname" => "templates", 
													"subdirs" => array( 
																	"default" => array( "dirname" => "default",
																						"subdirs" => array(
																										"styles" => array( "dirname" => "styles"),
																										"images" => array( "dirname" => "images")
	)
	)
	)
	),
								"views" => array( "dirname" => "views", 
												  "subdirs" => array( 
												  					"welcome" => array( "dirname" => "welcome")
	)
	)
	);

	public function __construct( )
	{
		$this->appDirectory = realpath( getcwd( ) . "/../../") . "/";
		echo "\n\n";
		echo sprintf( "bounce-Framework %s\n", APPVERSION);
		echo "---------------------------------------------------\n";
		echo "Released under GNU Public License V3 (GPLv3)\n";
		echo "\n\n";
		echo "Working Directory: " . $this->appDirectory . "\n";
			
		$this->basePath = $this->Prompt( "Enter the base 'path to' for the application: ", MAX_PATH_SIZE) . "/";
			
		if( strlen( $this->basePath) > 1 && !in_array( strtolower( $this->basePath), $this->invalidDirs ))
		{
			if( !is_dir( $this->basePath))
			{
				echo "Directory {$this->basePath} does not exist, create it? (y/n): ";

				if( $this->ReadAnswer( 'y'))
				mkdir( $this->basePath);
			}

			if( is_dir( $this->basePath))
			{
				$this->siteTitle = $this->Prompt( "Provide a title for the site: ");

				echo "Would you like to configure a database connection: ";
					
				if( $this->ReadAnswer( 'y'))
				{
					echo "If you wish to create the database, please make sure the user details entered\n" .
							"are those of a user able to drop/create databases.\n";

					$this->databaseConnectionName = $this->Prompt( "Name the database connection: ");
					$this->databaseConnector = $this->Prompt( "Specify the connector[MysqliConnector]: ", 255, "MysqliConnector");
					$this->databaseServer = $this->Prompt( "Database Server[localhost]: ", 255, "localhost");
					$this->databaseName = $this->Prompt( "Database Name: ");
					$this->databaseUser = $this->Prompt( "Database Username: ");
					$this->databasePass = $this->Prompt( "Database Password: ");

					$this->databaseXml = "<{$this->databaseConnectionName}>\n";
					$this->databaseXml .= "	<key name=\"connector\" value=\"{$this->databaseConnector}\" />\n";
					$this->databaseXml .= "	<key name=\"host\" value=\"{$this->databaseServer}\" />\n";				
					$this->databaseXml .= "	<key name=\"database\" value=\"{$this->databaseName}\" />\n";					
					$this->databaseXml .= "	<key name=\"username\" value=\"{$this->databaseUser}\" />\n";
					$this->databaseXml .= "	<key name=\"password\" value=\"{$this->databasePass}\" />\n";

					echo "WARNING\n";
					echo "The user details entered must be able to drop/create databases on the server.\n";
					echo "It is recommended that you change the username/password used after installing the database.\n";
					echo "Would you like to to create the database once installed? [Y/N]: ";

					if( $this->ReadAnswer( 'y'))
					{
						$this->installDb = true;
						
						$this->databaseXml .= "	<value name=\"create\" value=\"true\" />\n";
					}
						
					$this->databaseXml .= "</{$this->databaseConnectionName}>";
				}
					
				$this->configuration = file_get_contents( "defaults/default_configuration.xml");

				$this->configuration = str_replace( "{APPVERSION}", APPVERSION, $this->configuration );
				$this->configuration = str_replace( "{CONNECTION_NAME}", $this->databaseConnectionName, $this->configuration);
				$this->configuration = str_replace( "{DATABASE_XML}", trim( $this->databaseXml), $this->configuration);
				$this->configuration = str_replace( "{SITETITLE}", trim( $this->siteTitle), $this->configuration);
					
				$this->proceed = true;
			} 
			else
			{
				$this->proceed = false;
			}
		} 
		else
		{
			echo "\n!!Invalid installation directory {$this->basePath}\n";

			$this->proceed = false;
		}
	}

	public function PrepareScript(  )
	{
		$setupSql = file_get_contents( $this->appDirectory . "/core/scripts/install.sql");
				
		$setupSql = str_replace( "{DBNAME}", trim( $this->databaseName), $setupSql);

		if( chdir( $this->basePath ))
		{
			$createConfiguration = fopen( $this->basePath . "/core/install.sql", "w");

			fwrite( $createConfiguration, $setupSql );

			fclose( $createConfiguration );
		}
	}

	public function Install( )
	{
		if( $this->proceed)
		{
			echo "Proceed with installation to {$this->basePath}(y/n): ";

			if( $this->ReadAnswer( 'y'))
			{
				if( chdir( $this->basePath ))
				{
					//Create the directory structure
					foreach( $this->directories as $directory)
					{
						$this->CopyDirectory( $this->appDirectory, $this->basePath, $directory );
					}

					// Output the configuration
					$createConfiguration = fopen( $this->basePath . "/core/configuration.xml", "w");

					fwrite( $createConfiguration, $this->configuration);

					fclose( $createConfiguration);
				}
				else
				{
					echo "Unable to set basePath";
				}
			}
			
			if( $this->installDb )
			{
				$this->PrepareScript( );
			}			
		}
	}

	private function ReadAnswer( $expectedAnswer )
	{
		fseek( STDIN, 0);
			
		return strtolower(trim( fread( STDIN, 1))) == $expectedAnswer ? true : false;
	}

	private function Prompt( $prompt, $length = 255, $default = "" )
	{
		fseek( STDIN, 0);
			
		echo $prompt;
			
		$response = trim( fread( STDIN, $length));
			
		if( strlen( $response ) == 0 )
		$response = $default;
			
		return $response;
	}

	private function CopyDirectory( $fromDirectory, $root, $directory )
	{
		$fromDirectory = $fromDirectory . $directory[ "dirname"] . DIRSEP;
		$toDirectory = $root . $directory[ "dirname"] . DIRSEP;
			
		if( !file_exists( $toDirectory))
		{
			echo "Creating directory {$toDirectory}\n";
			mkdir( $toDirectory );
		}
			
		if( isset( $directory[ "files"]))
		$files = $directory[ "files"];
		else
		$files = scandir( $fromDirectory);
			
		foreach( $files as $file )
		{
			if( $file != ".." && $file != ".")
			{
				if( !is_dir( $fromDirectory . $file))
				{
					$fromFile = $fromDirectory . $file;
					$toFile = $toDirectory . $file;
					echo "Creating {$toFile}\n";

					if( file_exists( $toFile))
					{
						echo "File {$toFile} exists in target directory, overwrite? (y/n): ";
							
						if ( $this->ReadAnswer('y'))
						copy( $fromFile, $toFile);
					} else
					copy( $fromFile, $toFile);
				}
			}
		}
			
		if( isset( $directory[ 'subdirs']) && is_array( $directory[ 'subdirs']))
		{
			foreach( $directory[ 'subdirs'] as $subDirectory)
			{
				$this->CopyDirectory( $fromDirectory, $toDirectory, $subDirectory );
			}
		}
	}
}

$installer = new Install( );

$installer->Install( );

?>
