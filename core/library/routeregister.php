<?php
/*
	Bounce Framework - Welcome Controller 
	
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

interface IRouteRegister
{
    function MatchRoute($route);
}

class RouteRegister implements IRouteRegister
{
	private static $_instance;
	private $_configuration;
	private $_routes;
	private $_areas;

	private function __construct() {
		$this->_configuration = Configuration::GetSite();
		$this->_routes = array();
		$this->_areas = array( );
	}

	public static function & GetInstance() {
		if (!isset(self::$_instance)) {		
			$c = __CLASS__;

            self::$_instance = new $c();
            self::$_instance->RegisterRoutes( );
        }

		return self::$_instance;
	}
		
	public function RegisterRoutes( ) 
	{	
		foreach( $this->_configuration->paths as $directory)
		{					
			$this->ScanDirectory( ROOT_PATH . $directory);			
		}
	}
	
	protected function ScanDirectory( $directory )
	{
		if( is_dir( $directory)) {
			if( $handle = opendir($directory)) {
                $this->SearchForRegistration($handle, $directory);

                closedir( $handle);
			}				
		}
	}

    private function SearchForRegistration($handle, $directory)
    {
        while (($entry = readdir($handle))) {
            $path = $directory . "/" . $entry;

			if( file_exists($directory . "/ignore"))
           		continue;
           	
            if ($entry != ".." && $entry != ".") {

                if (is_dir($path)) {
                    $this->ScanDirectory($path);
                } else {
                    $info = pathinfo($path);

                    if ($info["extension"] == "php") {
                        if ($this->ContainsClassOfSameName($path, $info["filename"]) &&
                            $this->FilenameContainsRegistration($info)) {
                            $registerType = new ReflectionClass($info["filename"]);

                            if ($registerType->implementsInterface("IRouteRegistration")) {
                                if ($registerType->isInstantiable()) {
                                    $registration = $registerType->newInstance();

                                    $registration->Register($this);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function FilenameContainsRegistration($info)
    {
        return stripos($info['filename'], "registration") > -1;
    }

    public function ContainsClassOfSameName( $path, $name) {
		$file = file_get_contents($path);
		
		return stripos( $file, "class ${name}") != FALSE;
	}


	public function RegisterArea( $area) {
		$this->_areas[] = $area;	
	}
	
	public function AddRoute($routeName, $routePattern) {
		$this->_routes[] = array( "name" => $routeName, "pattern" => $routePattern );
	}
	
	public function MatchRoute( $route) {
 		$helper = new Helper( );
		$result = null;
		$uriElements = explode( "/", substr($route, 1));
								
		if( count($uriElements) > 1 ) {
			if( in_array( $uriElements[0], $this->_areas )) {
                $area = $uriElements[0];

                if( stripos($uriElements[1], "?")){
                    $components = explode("?", $uriElements[1]);
                    $method = null;
                    $controller = $components[0];

                    $parameters = array();

                    foreach(explode("&", $components[1]) as $paramElements){
                        $properties = explode("=", $paramElements);

                        $parameters[$properties[0]] = $properties[1];
                    }
                } else {
                    $controller = $uriElements[1];

                    $method = count($uriElements) > 2 ? $uriElements[2] : null;
                    $parameters = count($uriElements) > 3 ? array_splice($uriElements, 3) : null;
                }

                $fileToInclude = ROOT_PATH . "/areas/" . $area . "/controllers/" . $controller . ".php";

                $helper->Load( $fileToInclude);

                $result = Route::FromParameters($area, $controller, $method, $parameters);

            }
		}
		
		return $result;
	}
}

?>
