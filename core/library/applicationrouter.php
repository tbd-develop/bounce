<?php
/*
	Bounce Framework - Application Router 
	
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

class ApplicationRouter
{
    private $_controller;
    private $_view;
    private $_configuration;
    private $_routeConfiguration;
    private $_renderer;

    public function __construct(IConfiguration $configuration,
                                IRouteRegister $routeconfiguration)
    {
        $this->_configuration = $configuration->GetSite();

        $this->_controller = $this->_configuration->routes->defaultController;
        $this->_view = $this->_configuration->routes->defaultView;

        $this->_routeConfiguration = $routeconfiguration;

        $this->_renderer = Renderer::GetInstance( );
    }

    public function GoRoute($route)
    {
        if (isset($route)) {
            $matchedRoute = $this->_routeConfiguration->MatchRoute($route);

            if ($matchedRoute == null)
                $this->ExecuteRoute(new Route($route));
            else
                $this->ExecuteRoute($matchedRoute);
        }
    }

    protected function ExecuteRoute(Route $route)
    {
        try
        {      
            $controllerName = $route->GetArea() != null ? $route->GetArea() . "\\" . $route->GetController() : $route->GetController();

            $controllerType = new ReflectionClass($controllerName);
        }
        catch (ReflectionException $exc)
        {
            $controllerType = new ReflectionClass($this->_controller);
        }

        try
        {
            if ($controllerType->isInstantiable()) {
                $this->BuildAndInvokeControllerMethodCall($controllerType, $route);
            }
        }
        catch(Exception $exc)
        {
            http_response_code(500);
            throw $exc;
        }
    }

    private function BuildAndInvokeControllerMethodCall($controllerType, Route $route)
    {
        $result = null;

        $controllerInstance = $controllerType->newInstance();

        $permitted = true;

        if ($controllerType->hasMethod("RequiredRole") && $controllerInstance->RequiredRole() != null) {
            $currentUser = UserFactory::GetInstance();

            $roles = $controllerInstance->RequiredRole();

            $permitted = $currentUser->IsPermitted(is_array($roles) ? $roles[0] : $roles);
        }

        $controllerInstance->SetArguments($route->GetParams());
        $controllerInstance->SetArea($route->GetArea());

        if ($permitted)
        {
            $methodNameToCall = $route->GetMethod() && $controllerType->hasMethod($route->GetMethod()) ? 
                                    $route->GetMethod() :  
                                    (
                                        $controllerType->hasMethod(strtolower($_SERVER['REQUEST_METHOD'])) ?
                                            strtolower($_SERVER['REQUEST_METHOD']) : "index"
                                    );

            $method = new ReflectionMethod($controllerType->getName(), $methodNameToCall);

            if( !isset($method))
                throw new Exception("Method ${methodNameToCall} not supported on {$route->GetController()}");

            if( count($route->GetParams()) > 0 || count($_POST) > 0) {
                if( count($_POST) > 0) 
                    $params = $this->ModelBind($route, $method);
                else
                    $params = $this->ParameterBind($method, $route->GetParams());

                $result = $method->invokeArgs($controllerInstance, $params);
            }
            else
            {
                if( $method == "delete" ||
                    isset( $_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json')
                {
                    $jsonData = file_get_contents('php://input');

                    $params = $this->JsonDataBind($method, $jsonData);

                    $result = $method->invokeArgs($controllerInstance, $params);
                } else
                    $result = $method->invoke($controllerInstance);
            }
        }
        else
        {
            if ($controllerType->hasMethod("NotPermitted"))
            {
                $method = new ReflectionMethod($controllerType->getName(), "NotPermitted");

                $result = $method->Invoke($controllerInstance);
            }
            else
                throw new Exception("You are not permitted to view the resource");
        }

        if($controllerInstance instanceof IRequireNoRendering)
            return;

        if( $result instanceof IRedirect)
            $result->Render();
        else
            $this->_renderer->RenderResult($result, $route);
    }

    private function ParameterBind( $method, $params)
    {
        $result = array();

        $methodParameters = $method->getParameters();

        foreach ($methodParameters as $parameter)
        {
            $parameterName = $parameter->getName();

            if( array_key_exists($parameterName, $params))
                $result[$parameterName] = $params[$parameterName];
            else {
                foreach( $params as $cnt => $arg) {
                    if( is_array($arg)) 
                        if( isset($arg[$parameter->getName()] ))
                            $result[] = $arg[$parameter->getName()];
                    else 
                        $result[$parameter->getName()] = $params[$cnt];
                }
            }
        }

        return $result;
    }

    public function ModelBind($route, $method)
    {
        $params = array();

        if( $method->getNumberOfParameters() > 0 )
        {
            $methodParameters = $method->getParameters();

            foreach ($methodParameters as $param) {
                if( $param->getClass( ) != null ) {
                    $typeArg = $this->GetTypeParameter($param);

                    if (!class_exists($typeArg))
                        break;

                    $classType = new ReflectionClass($typeArg);
                    $classInstance = $classType->newInstance();

                    $properties = $classType->getProperties(ReflectionProperty::IS_PUBLIC);

                    foreach ($properties as $property)
                        if (isset($_POST[$property->name]))
                            $property->setValue($classInstance, $_POST[$property->name]);

                    $params[] = $classInstance;
                }
                else
                    $params[$param->getName()] = $_POST[$param->getName()];
            }

            if (count($params) == 0)
                $this->ParameterBind( $method, $route->GetParams());
        }

        return $params;
    }

    protected function JsonDataBind($method, $jsonParameters) {
        $params = array();

        if( $method->getNumberOfParameters() > 0) {
            $formData = json_decode($jsonParameters);

            $methodParameters = $method->getParameters();

            foreach($methodParameters as $param) {
                $params[$param->getName()] = $formData;
            }
        }

        return $params;
    }

    /*
      * http://stackoverflow.com/a/4514029/263651
      * Thanks to netcoder
      * */
    protected function GetTypeParameter(ReflectionParameter $param)
    {
        $export = ReflectionParameter::export(
            array(
                $param->getDeclaringClass()->name,
                $param->getDeclaringFunction()->name
            ),
            $param->name,
            true
        );

        $type = preg_replace('/.*?(\w+)\s+\$' . $param->name . '.*/', '\\1', $export);

        return $type;
    }
}

?>
