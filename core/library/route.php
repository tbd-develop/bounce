<?php
class RouteParameters
{
    const ControllerIdx = 1;
    const MethodIdx = 2;
    const ParamsIdx = 3;
}

class Route
{
    private $_area;
    private $_controller;
    private $_method;
    private $_params;
    private $_routePath;

    public function __construct($routePath)
    {
        if( $routePath > '') {
            $this->_routePath = $routePath;

            $this->PopulateRouteSettings();
        }
    }

    public static function FromParameters($area, $controller, $method, $params) {
        $route = new Route('');

        $route->_area = $area;
        $route->_controller = $controller;

        if(isset( $method) && stripos($method, "?")) {
            $route->_method = $route->GetMethodNameFromElementString($method);
            $params = $route->ExtractParamsFromQueryString($method);
        }
        else {
            if(isset($method))
                $route->_method = $method;
        }

        $route->_params = $params;

        return $route;
    }

    public function GetController() {
        return $this->_controller;
    }

    public function GetArea() {
        return $this->_area;
    }

    public function GetMethod() {
        return $this->_method;
    }

    public function GetParams() {
        return $this->_params;
    }

    private function PopulateRouteSettings()
    {
        $pathToExtract = explode("?", $this->_routePath);

        $elements = explode("/", $pathToExtract[0]);

        $this->_area = null;
        $this->_controller = null;
        $this->_method = null;
        $this->_params = null;

        $this->_controller = $elements[RouteParameters::ControllerIdx];

        if (isset($elements[RouteParameters::MethodIdx])) {
            $methodName = $this->GetMethodNameFromElementString($elements[RouteParameters::MethodIdx]);

            $this->_method = $methodName;

            $this->_params = empty($_POST) ? sizeof($pathToExtract) > 1 ? $this->ExtractParamsFromQueryString($pathToExtract[1]) :
                $this->ExtractOtherElementsToParams($elements) :
                $this->ExtractParamsFromPost($_POST);

            if( sizeof($this->_params) == 0 ) {
                $this->_params = $this->ExtractOtherElementsToParams($elements);
            }
        }
    }

    private function GetMethodNameFromElementString($method) {
        $result = $method;

        if( stripos( $method, "?") > -1) {
            $elements = explode("?", $method);

            $result = $elements[0];
        }

        return $result;
    }

    public function ExtractParamsFromQueryString($parameters) {
        $result = array();
        $urlComponents = explode('?', $parameters);

        if( sizeof($urlComponents) > 1) {
            $action = $urlComponents[0];
            $arguments = $urlComponents[1];
            $queryParams = explode("&", $arguments);

            foreach($queryParams as $param) {
                $components = explode("=", $param);
                $result[$components[0]] =  $components[1];
            }
        }

        return $result;
    }

    public function ExtractParamsFromPost()
    {
        $result = array();

        foreach( $_POST as $key => $value ) {
            $result[$key] = $value;
        }

        return $result;
    }

    private function ExtractOtherElementsToParams($elements)
    {
        if (sizeof($elements) > 2) {
            $params = array_slice($elements, 3);

            return $params;
        }


        return null;
    }
}
?>