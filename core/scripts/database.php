<?php
	if( file_exists( "../configuration.xml"))
	{	
		if( $argc == 1)
		{
			echo "\n";
			echo "Usage: php database.php <dbname> [host] [user] [pass] [connector]";
			echo "\n";
		} else
		{
			$dbname = "";
			$dbhost = "";
			$dbuser = "";
			$dbpass = "";
			$dbconn = "";
			
			switch( $argc)
			{
				case 2:
					{
					$dbname = $argv[1];
					}break;
				case 3:
					{
						$dbname = $argv[1];
						$dbhost = $argv[2];
					}break;
				case 4:
					{
						$dbname = $argv[1];
						$dbhost = $argv[2];
						$dbuser = $argv[3];
					}break;
				case 5:
					{
						$dbname = $argv[1];
						$dbhost = $argv[2];
						$dbuser = $argv[3];
						$dbpass = $argv[4];
					}break;
				case 6:
					{
						$dbname = $argv[1];
						$dbhost = $argv[2];
						$dbuser = $argv[3];
						$dbpass = $argv[4];
						$dbconn = $argv[5];
					}break;
				default:
					{
						echo "\n\n - Invalid or unrecognised number of arguments\n\n";
					}break;				
			}
		}
		
		function Prompt( $prompt, $length = 255)
		{
			fseek( STDIN, 0);
			
			echo $prompt;
			
			$response = trim( fread( STDIN, $length));
			
			return $response;
		}		
	} 
	else
	{
		echo "\n";
		echo " No valid configuration file for current installation ";
		echo "\n";		
	}
?>