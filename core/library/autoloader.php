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
				$this->_configuration = Configuration::GetSite( );

			if( $this->_searchTree == null)
				$this->BuildSearchTree();

			if( !$this->LoadClass(strtolower($className)))
				throw new Exception("Could not load class with name {$className}"); 
		}

		private function BuildSearchTree() 
		{
			$this->_searchTree = array();

			foreach( $this->_configuration->paths as $directory)
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
					if( file_exists($directory . "/ignore"))
						continue;
					
					$fullFilePath = $directory . "/" . $entry;
                    $entry = strtolower($entry);

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

		private function LoadClass( $className)
  		{
  			$result = false;
  			$filename = "{$className}.php";

  			if( array_key_exists(strtolower($filename), $this->_searchTree))
  			{
  				$paths = $this->_searchTree[$filename];

                if( sizeof($paths) > 1) {
                    foreach($paths as $path) {
                        if( stripos($path, 'areas') == false){ // Areas are handled by themselves
                            require_once($path . "//" . $filename);

                            $result = true;

                            break;
                        }
                    }

                    if( $result == false)
                        require_once($paths[0] . "//" . $filename);
                } else
				    require_once($paths[0] . "//" . $filename);

				$result = true;
  			} else {
  				foreach($this->_searchTree as $key => $value) {
					$pathinfo = pathinfo($key);
					$name = $pathinfo['filename'];

  					if( stripos($className, strtolower($name)) > -1) {
  						$paths = $this->_searchTree[$key];

						foreach($paths as $path) {
	  						$fileContents = file_get_contents($path . "/" . $key);

	  						$classes = $this->get_php_classes($fileContents);

	  						if( in_array(strtolower($className), $classes)){
	  							require_once($path . "//" . $key);

	  							$result = true;
  							}
  						}
  					}
  				}
  			}
  			
  			return $result;
  		}

  		function get_php_classes($php_code) {
			$classes = array();
			$tokens = token_get_all($php_code);
			$count = count($tokens);
  			
  			for ($i = 2; $i < $count; $i++) {
    			if ( $tokens[$i - 2][0] == T_CLASS
        			&& $tokens[$i - 1][0] == T_WHITESPACE
        			&& $tokens[$i][0] == T_STRING) {					
					$class_name = strtolower($tokens[$i][1]);
					$classes[] = $class_name;
    			}
  			}
  
  			return $classes;
		}
	}

?>
