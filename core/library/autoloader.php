<?php

	class AutoLoader
	{
		public function __construct() 
		{
			spl_autoload_register(array($this, 'loader'));
		}		
		
		public function loader($className) 
		{
			$configuration = SimpleConfiguration::GetInstance( );

            $directoryList = $configuration->GetSettingsCollection('directories');

            foreach( $directoryList as $key => $directory)
            {
                if( $this->CheckDir( ROOT_PATH . $directory, strtolower( $className)))
                    break;
            }
		}
		
		public function CheckDir( $directory, $classname)
  		{
  			$result = false;			
  			
  			if( !file_exists( "{$directory}/{$classname}.php"))
  			{
  				if( !file_exists( "{$directory}/exclude"))
  				{					
  					if( file_exists( $directory))
  					{					
						$dirInfo = dir( $directory);
			        		
						while( ($subdirectory = $dirInfo->Read( )) !== false)
			        	{       						
							$searchSubDirectory = $directory . "/" . $subdirectory;
							
							if( is_dir( $searchSubDirectory ) && $subdirectory != ".." && $subdirectory != ".")		
							{
								if( $this->CheckDir( $searchSubDirectory, $classname))
								{
									$result = true;
									break;
								}					
							}
						}
  					}
  				}  	        
  			} 
  			else
  			{
	  			include_once( "{$directory}/{$classname}.php");
  				$result = true; 
  			}  		
  		
  			return $result;
  		}
	}

?>
