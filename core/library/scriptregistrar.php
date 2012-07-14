<?php
/*
	bounce Framework - Base Controller class
	
    Copyright (C) 2011, 2012  Terry Burns-Dyson

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

class ScriptRegistrar
{
	private static $_instance;
  	private $_session;
  	private $_scripts;
  	private $_scriptsPath;
  	
  	public function __construct( )
    {
      $this->_configuration = SimpleConfiguration::GetInstance( );
      $this->_session = Session::GetInstance( );
      
      $this->_scriptsPath = $this->_configuration->GetSetting("defaults","scripts-directory");
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
		$this->_scripts = array();
	}
    
    public function AddScript( $script, $renderFromTemplate = false ) 
    {
		if( !array_key_exists( $script, $this->_scripts)) 
		{
			array_push($this->_scripts, array( "script" => $script, "renderFromTemplate" => $renderFromTemplate));
		}
	}
	
	public function Render( $template ) 
	{
		$outhtml = "";

		foreach( $this->_scripts as $key => $value) 
		{
            $script = $value["script"];

            $renderFromTemplate = $value["renderFromTemplate"];

			if( !empty($template) && $renderFromTemplate)
			{
				$scriptToLoad = $template . $script;
			}
            else if( stripos($script, 'http') !== false && stripos($script, 'http') == 0)
            {
                $scriptToLoad = $script;
            }
			else if( !empty($this->_scriptsPath))
			{
				$scriptToLoad = $this->_scriptsPath . $script;
			} 
			else 
			{
				$scriptToLoad = "";
			}

			if( !empty($scriptToLoad)) 
			{
				$outhtml .= "<script type=\"text/javascript\" src=\"{$scriptToLoad}\"></script>\r\n";
			}			
		}
		
		echo $outhtml;
	}
}

?>
