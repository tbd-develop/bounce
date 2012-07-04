<?php
/*
	bounce Framework - Base Controller class
	
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
  abstract class Controller implements IController
  {
    protected $_arguments;
    protected $_configuration;
    protected $_params;
    protected $_includes;
    protected $_model;
	protected $_area;
    
    public function __construct( )
    {
    	$this->_params = array( );
    	$this->_includes = array( );
      	
    	if( func_num_args( ) > 0)    	   
        	$this->_arguments = func_get_arg( 0 );
        
      	$this->_configuration = Configuration::GetInstance( );
      
      	$this->Load =& Renderer::GetInstance( );
		$this->Service =& RESTService::GetInstance();
    }
	    
    public function View($viewName, $model = null) {
        $view = new View($this, $viewName);

        if( $model)
            $view->Model = $model;

        return $view;
	}

    public function Json($model) {
        return new Json($this, $model);
    }

	public function PartialView( $viewName, $model = null) {
        $view = new PartialView($this, $viewName);

        if( $model)
            $view->Model = $model;

        return $view;
	}

    public function File( $filePath) {
        $this->Load->SendFile($filePath);
    }

	public function Redirect( $url ) {
		header( "Location: {$url}" );
	}
	
	public function SetArea( $area) {
		$this->_area = $area;
	}
	
	public function GetArea( ) {
		return $this->_area;
	}

    public function SetArguments( $args )
    {
    	$this->_arguments = $args;
    }    
    
    public function index( ) { }
  }
?>
