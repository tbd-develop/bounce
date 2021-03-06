<?php
/*
	Bounce Framework - Base Controller class
	
    Copyright (C) 2011  Terry Burns-Dyson

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

class StylesheetRegistrar
{
    private static $_instance;
  	private $_session;
  	private $_stylesheets;
  	
  	public function __construct( )
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
    
    public function Clear() 
    {
		$this->_stylesheets = array();
	}
    
    public function AddStylesheet( $stylesheet, $inTemplate = true)
    {
        if( !array_key_exists( $stylesheet, $this->_stylesheets)) {
            array_push($this->_stylesheets, array("sheet" => $stylesheet, "template" => $inTemplate));
          }
	}
	
	public function Render( $template) 
	{
		$outHtml = "";
		
		foreach( $this->_stylesheets as $stylesheet) 
		{
            $path = $stylesheet['sheet'];

            if( $stylesheet['template'])
                $outHtml .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$template}{$path}\" />\r\n";
            else {
                $path = $this->CssFileIsShared($path);

                $outHtml .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$path}\" />\r\n";
            }
		}
		
		echo $outHtml;
	}

  private function CssFileIsShared($filename) {
    $templates = $this->_configuration->GetSite()->template->directory;

    $path = $templates . "/shared/" . $filename;

    if( file_exists(ROOT_PATH . $path))
      return $path;

    return $filename;
  }
}

?>
