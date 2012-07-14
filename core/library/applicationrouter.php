<?php
/*
	bounce Framework - Application Router
	
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

class ApplicationRouter
{
    private $_controller;
    private $_view;
    private $_configuration;
    private $_routeConfiguration;
    private $_renderer;

    public function __construct($configuration,
                                IRouteRegister $routeconfiguration)
    {
        $this->_configuration = $configuration;

        $this->_controller = $this->_configuration->GetSetting("routes", "default_controller");
        $this->_view = $this->_configuration->GetSetting("routes", "default_view");

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

        if ($controllerType->isInstantiable()) {
            $this->BuildAndInvokeControllerMethodCall($controllerType, $route);
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

        if ($permitted) {
            if ($route->GetMethod())
            {
                if ($controllerType->hasMethod($route->GetMethod()))
                {
                    $method = new ReflectionMethod($controllerType->getName(), $route->GetMethod());

                    if (count($route->GetParams()) > 0 || count($_POST) > 0)
                    {
                        if (count($_POST) > 0)
                            $params = $this->ModelBind($route, $method);
                        else
                            $params = $this->ParameterBind($method, $route->GetParams());

                        $result = $method->invokeArgs($controllerInstance, $params);
                    }
                    else
                        $result = $method->invoke($controllerInstance);
                }
                else
                {
                    $method = $route->GetMethod();
                    $controller = $route->GetController();

                    throw new Exception("Method ${method} not supported on ${controller}");
                }
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

        if( $result instanceof IRedirect)
            $result->Render();
        else
            $this->_renderer->RenderResult($result);
    }

    private function ParameterBind( $method, $params) {
        $result = array();

        $methodParameters = $method->getParameters();

        foreach ($methodParameters as $parameter)
        {
            foreach( $params as $cnt => $arg) {
                if( is_array($arg)) {
                    if( isset($arg[$parameter->getName()] ))
                    {
                        $result[] = $arg[$parameter->getName()];
                    }
                }
                else {
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

                    if (!class_exists($typeArg)) {
                        break;
                    }

                    $classType = new ReflectionClass($typeArg);
                    $classInstance = $classType->newInstance();

                    $properties = $classType->getProperties(ReflectionProperty::IS_PUBLIC);

                    foreach ($properties as $property)
                    {
                        if (isset($_POST[$property->name]))
                            $property->setValue($classInstance, $_POST[$property->name]);
                    }

                    $params[] = $classInstance;
                }
                else
                {
                    $params[$param->getName()] = $_POST[$param->getName()];
                }
            }

            if (count($params) == 0) {
                $this->ParameterBind( $method, $route->GetParams());
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
