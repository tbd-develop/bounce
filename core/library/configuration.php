<?php
/*
	bounce Framework - Configuration object
	
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
interface IConfiguration extends ArrayAccess
{
	function GetSitePath($configurationNode, $configurationElement );
    function Load($configurationFile);
}

  class Configuration implements IConfiguration
  {
    private static $_configuration;
    private static $_instance;
  
    private function __construct( $config )
    {    
      self::$_configuration = array( );
      $currentElement = null;
      
      if( strlen( $config) > 0)
      {
        $xmlReader = new XmlReader( );
                
        $xmlReader->open( $config );      
        
        $xmlReader->read( );     
        
        do 
        {
          switch( $xmlReader->nodeType)
          {
            case XMLReader::ELEMENT:
            {
              if( $xmlReader->depth == 1)
              {
                $currentElement = $xmlReader->name;
              
                if( !isset( self::$_configuration[ $currentElement]))
                {
                  self::$_configuration[ $currentElement] = array( );
                }                          
              } 
              else 
              {              
                if( isset( $currentElement ))
                {
                  if( $xmlReader->hasAttributes)
                  {                      
                    $currentAttrib = null;              
                    $xmlReader->moveToFirstAttribute( );
                    
                    do 
                    {
                      switch( strtolower( $xmlReader->name))
                      {
                        case 'name':
                        {
                          $currentAttrib = $xmlReader->value;
                        }break;
                        case 'value':
                        {
                          if( $currentElement != null)                  
                            self::$_configuration[ $currentElement][ $currentAttrib] = $xmlReader->value;                            
                          
                          $currentAttrib = null;
                        }break;
                      }
                    }while( $xmlReader->moveToNextAttribute( ));
                  }
                } 
              }         
            }break;
            case XMLReader::END_ELEMENT:
            {
              $currentElement = null;
            }break;
          }
                     
        } while( $xmlReader->read( ));
        
        $xmlReader->close( );
      } 
    }
    
    public static function &GetInstance( )
    {
      if( !isset( self::$_instance))
        self::$_instance = new Configuration( "configuration.xml");
      
      return self::$_instance;
    } 

	public function GetSitePath($configurationNode, $configurationElement )
	{
		if( isset(self::$_configuration[$configurationNode]) && 
			isset(self::$_configuration[$configurationNode][$configurationElement])) 
		{
            $siteprofile = self::$_configuration['configuration']['siteprofile'];

			$rootPath = self::$_configuration[$siteprofile]["rootpath"];
			$path = self::$_configuration[$configurationNode][$configurationElement];
			
			if( is_dir($rootPath . $path)){
				return HTTP_ROOT . $path;	
			}
		}

		return "";
	}
    
    public function AddConfig( $name, $value)
    {
      if( isset( self::$_configuration[ $name]))
        self::$_configuration[ $name] = array_merge( self::$_configuration[ $name], $value);
      else
        self::$_configuration[$name] = $value;
    }       
   
    public function Load( $configurationFile) 
    {
      if( !isset( self::$_instance))
        self::$_instance = new Configuration( $configurationFile );
    } 
    
    /*
    
      --------------------------------------------------------------
    
      ArrayAccess
      
      --------------------------------------------------------------
    
    */
    
    function offsetExists( $name )
    {      
      return isset( self::$_configuration[ $name]);
    }
    
    function offsetGet( $name)
    {
      return isset( self::$_configuration[ $name]) ? self::$_configuration[ $name] : null;
    }
    
    function offsetSet( $name, $value)
    {
      array_push( self::$_configuration, array( $name => $value));
    }
    
    function offsetUnset( $name)
    {
      unset( self::$_configuration[ $name]);
    }
  }

?>