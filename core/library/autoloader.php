<?php

	class AutoLoader
	{
		private $_configuration;
		private $_searchTree;

		public function __construct() 
		{
			spl_autoload_register(array($this, 'loader'));			
		}		
		
		public function loader($className) 
		{
			if( $this->_configuration == null) 
				$this->_configuration = Configuration::GetInstance( );	

			if( $this->_searchTree == null)
				$this->BuildSearchTree();

    		if( isset( $this->_configuration))
    		{    	
				foreach( $this->_configuration[ "directories"] as $directory)
				{
					if( $this->CheckDir( ROOT_PATH . $directory, strtolower( $className)))
						break;
				}
    		} 
		}

		private function BuildSearchTree() 
		{
			$this->_searchTree = array();

			foreach( $this->_configuration["directories"] as $directory) 
			{
				$this->SearchTreeNode(ROOT_PATH . $directory);
			}
		}

		private function SearchTreeNode($directory)
		{
			if( !is_dir($directory))
				return;

			$dirInfo = dir( $directory);

			while($entry = $dirInfo->Read()) 
			{
				if( $entry != "." && $entry != "..") 
				{
					$fullFilePath = $directory . "/" . $entry;

					if( is_file($fullFilePath))
					{
						$path = pathinfo($entry);

						if( $path["extension"] === "php") 
						{					
							if( array_key_exists($entry, $this->_searchTree))
							{
								array_push($this->_searchTree[$entry], $directory);
							} else {
								$this->_searchTree[$entry] = array( $directory );
							}
						}
					} 
					elseif ( is_dir( $fullFilePath))
					{
						$this->SearchTreeNode($fullFilePath);
					} 
				}
			}
		}
		
		public function CheckDir( $directory, $className)
  		{
  			$result = false;
  			$filename = "{$className}.php";

  			if( array_key_exists($filename, $this->_searchTree))
  			{
  				$paths = $this->_searchTree[$filename];

				require_once($paths[0] . "//" . $filename);

				$result = true;
  			}
  			
  			return $result;
  		}
	}

?>
