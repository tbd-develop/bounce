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

  class Renderer
  {
    private static $_instance;
    private $_configuration;
    private $_session;

    private function __construct( )
    {
      $this->_configuration = Configuration::GetInstance( );
      $this->_session = Session::GetInstance( );
    }
    
    public static function &GetInstance( )
    {
      if( !isset( self::$_instance))
      {
      	$c = __CLASS__;
      	
        self::$_instance = new $c( );        
      }
      
      return self::$_instance;
    }

   public function RenderResult(View $view)
   {
        if( $view instanceof ServiceView)
            RESTService::GetInstance()->SetResponse($view->Controller, $view->ResponseName, $view->ResponseData(), $view->Type );
        else
            $this->OutputView($view);
   }

    public function SendFile($fileName) {
        $user = UserFactory::GetInstance();

        $userTheme = $user->getTheme();

        $template = $userTheme != null ? $userTheme : $this->_configuration[ 'configuration'][ 'default_template'];

        $filePath =  ROOT_PATH . $this->_configuration[ 'directories'][ 'templates'] .DIRSEP . $template . DIRSEP . $fileName;

        header('Content-Type: image/jpg');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);

        exit();
    }

    protected function OutputView( $view)
	{
        $controller = $view->Controller;
        $viewName = $view->ViewName;
        $model = $view->Model;
        $isPartial = $view instanceof IPartialView;

		if( strlen( $viewName) > 0)
		{
            ob_start( );

            $user = UserFactory::GetInstance();

            if( !$isPartial) // A partial view can add sheets etc. But should not empty out the current set
            {
                StylesheetRegistrar::GetInstance()->Clear();
                ScriptRegistrar::GetInstance()->Clear();
            }

			$controllerName = NameUtils::GetRealClassName($controller);

            $userTheme = $user->getTheme();
							
		    $template = $userTheme != null ? $userTheme : $this->_configuration[ 'configuration'][ 'default_template'];

			$view = $this->FindViewTemplate( $controllerName, $controller->GetArea(), $viewName);

			if( $view == null ) {
				throw new Exception( "Failed to load '${viewName}'");
			}
							
			$params[ 'template'] = HTTP_ROOT . $this->_configuration[ 'directories'][ 'templates'] . "/" . $template;
			$params[ 'library'] = HTTP_ROOT . $this->_configuration[ 'directories']['userlibs'];
			$params[ 'siteTitle'] = $this->_configuration[ "configuration"][ "siteTitle"];
            $params[ 'configuration'] = $this->_configuration['configuration'];
			$params[ 'controller'] = $controller;
            $params[ 'user'] = $user;
			$params[ 'viewdata'] = new ViewData();
            $params[ 'html'] = $this;
            $params[ 'Model' ] = $model;

			// Generate the view first
			$viewContent = $this->Compile( $view, $isPartial, $params);
			
			$params[ 'pageContent'] = $viewContent;
			
			// Include any additional includes as separate compiles but set them as parameters 
			// for our final page load
			
			$includes = $this->_configuration[ 'includes'];
			
			if( sizeof( $includes) > 0 )
			{			
				foreach( $includes as $key => $value)
				{					
					$viewToInclude = ROOT_PATH . $this->_configuration[ 'directories'][ 'includes'] . DIRSEP . "${value}";												
					
					$params[ $key] = $this->Compile( $viewToInclude, $isPartial, $params);
				}
			}
		
			if( !$isPartial)
			{
				// Now compile the default_pagebody with all that has come before
				$output = $this->Compile( ROOT_PATH . $this->_configuration[ 'directories'][ 'templates'] . 
								DIRSEP . $template . DIRSEP . "default_pagebody.html", $isPartial, $params);
								
				echo $output;
			} 
			else 
			{
				echo $viewContent;
			}

            ob_end_flush( );
		} 
		else 
		{
			throw new Exception( "No view was specified, invalid configuration");
		}
	}

    public function RenderPartial($view, $controller, $model = null)
    {
        $controllerType = new ReflectionClass($controller);
        $controller = $controllerType->newInstance();
        $controllerName = NameUtils::GetRealClassName($controller);

        $viewToRender = $this->FindViewTemplate( $controllerName, $controller->GetArea(), $view);

        $params = array();

        if(isset($model))
        $params["Model"] = $model;

        if( sizeof( $params) > 0)
          extract( $params, EXTR_PREFIX_SAME, "glob_");

        include( $viewToRender );
    }

    public function Partial($view, $controller, $params = null) {
        $controllerType = new ReflectionClass($controller);
        $controller = $controllerType->newInstance();

        if( $controllerType->hasMethod($view))
        {
            $method = new ReflectionMethod($controllerType->getName(), $view);

            $result = $method->invokeArgs($controller, (array) $params);

            $this->OutputView($result);
        }
    }

	private function FindViewTemplate( $controllerName, $area, $view) {
		$pathsToSearch = array();

        if( !empty($area)) {
			$pathsToSearch[] = ROOT_PATH . DIRSEP . "areas" . DIRSEP . $area . DIRSEP . "views" . DIRSEP . $controllerName;
			$pathsToSearch[] = ROOT_PATH . DIRSEP . "areas" . DIRSEP . $area . DIRSEP . "views" . DIRSEP . "shared"; 			
		} else {
			$pathsToSearch[] = ROOT_PATH . DIRSEP . "views" . DIRSEP . $controllerName;
		}
		
		$pathsToSearch[] = ROOT_PATH . DIRSEP . "views" . DIRSEP . "shared";
				
		foreach( $pathsToSearch as $search) {
			$testTemplate = $search . DIRSEP . "{$view}.html";
			
			if( file_exists( $testTemplate)) {
				return $testTemplate;
			}
		}		
		
		return null;
	}
	
	private function Compile( $file, $isPartial = false, $params = null)
	{
		if( sizeof( $params) > 0)
			extract( $params, EXTR_PREFIX_SAME, "glob_");

        ob_start();

		include( $file );

		return ob_get_clean( );
	}
  }

?>
