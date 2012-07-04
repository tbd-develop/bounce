<?php
/*
 bounce Framework - Renderer for page building and display

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

class RESTService {
	private static $_instance;
	private $_configuration;
	private $_session;

	private function __construct() {
		$this -> _configuration = Configuration::GetInstance();
		$this -> _session = Session::GetInstance();
	}

	public static function & GetInstance() {
		if (!isset(self::$_instance)) {
			$c = __CLASS__;

			self::$_instance = new $c();
		}

		return self::$_instance;
	}

	public function SetResponse($controller, $responseName, $params = null, $serviceType = 'json') {
		ob_start();
		
		$controllerName = NameUtils::GetRealClassName($controller);

		switch( $serviceType) {
			case "xml" :
				{
					if (strlen($responseName) > 0) {

						$template = $this -> _configuration['user']['template'];

						$viewDir = ROOT_PATH . $this -> _configuration['directories']['services'] . DIRSEP . $controllerName;
						$response = $viewDir . DIRSEP . "{$responseName}.xml";

						// Generate the response first
						$responseContent = $this -> Compile($response, $params);

						$data['responseContent'] = $responseContent;

						$output = $this -> Compile(ROOT_PATH . $this -> _configuration['directories']['services'] . DIRSEP . "serviceresponse.xml", $data);

						header('Content-Type: text/xml');

						$output = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n" . $output;
						echo $output;
					} else {
						throw new Exception("No response was specified, invalid configuration");
					}
				}
				break;
			case "json" :
				{
					header( "Content-Type: application/json");
					
					echo json_encode($params, JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG );
				}
				break;
            case "isjson" :
                {
                    header("Content-Type: application/json");

                    echo $params;
                }break;
		}

		ob_end_flush();
	}

	private function Compile($file, $params = null) {
		if (sizeof($params) > 0)
			extract($params, EXTR_PREFIX_SAME, "glob_");

		ob_start();

		include ($file);

		return ob_get_clean();
	}

}
