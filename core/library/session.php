<?php
	/*
	bounce Framework - Session management
	
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
    interface ISession
    {
        function GetSessionId();
    }

	class Session implements ArrayAccess, ISession
	{
		private $_configuration;
		private $_sessionExpiry;
		private $_cookieName;
		private $_cookiePath;
		private $_cookieDomain;
		private $_userData;
		private $_sessionId;
        private $_siteProfile;
		private static $_instance;
		
		private function __construct( )
		{
			$this->_userData = array( );
			$this->_configuration = Configuration::GetInstance( );
            $this->_siteProfile = $this->_configuration["configuration"]["siteprofile"];

			$this->_sessionExpiry = $this->_configuration[ "configuration"][ "sessionlength"];
			$this->_cookieName = $this->_configuration[ $this->_siteProfile][ "cookiename"];
			$this->_cookiePath = $this->_configuration[ $this->_siteProfile][ "cookiepath"];
			$this->_cookieDomain = $this->_configuration[ $this->_siteProfile][ "cookiedomain"];
		}
		
		public static function GetInstance( )
		{
			if( !isset( self::$_instance))
			{        						
				$c = __CLASS__;
				
				self::$_instance = new $c( );	
			} 			

			self::$_instance->Start( );
						
			return self::$_instance;
		}
		
		private function Start( )
		{			
			if( !$this->ReadCookie( ))
			{
				$this->_userData = array( );
				
				$this->_sessionId = $this->getUniqueId();
				
				$this->_userData["sessionid"] = md5( $this->_sessionId);
				$this->_userData["lastvisit"] = time( );
		
				$this->Write( );
			} 
		}

        public function getUniqueId() {
            $id = "";

            while( strlen( $id) < 32)
                $id .= mt_rand( 0, mt_getrandmax( ));

            return uniqid( $id);
        }
		
		public function GetSessionId( )
		{
			return $this->_userData[ "sessionid"];
		}
		
		private function ReadCookie( )
		{						
			if( isset( $_COOKIE[ $this->_cookieName]))
			{				
				$this->_userData = $this->array_explode( "=", "&", $_COOKIE[ $this->_cookieName]);
				
				return true;
			} 

			return false;
		}
		
		public function Write( )
		{
			$cookieData = $this->array_implode( "=", "&", $this->_userData);
			
			$sessionLength = time( ) + ( 60 * 60 * 24 * $this->_sessionExpiry );		
			
			setcookie( $this->_cookieName, 
						$cookieData,
						$sessionLength,						
						$this->_cookiePath );								
		}
		
		private function Clear( )
		{
			setcookie( $this->_cookieName, "", time() - 3600, $this->_cookiePath, $this->_cookieDomain);				
		}

        function array_explode( $glue, $seperator, $string) {
            $result = array();
            $arrayOfElements = explode( $seperator, $string);

            foreach( $arrayOfElements as $node){
                $elements = explode($glue, $node);
                $result[$elements[0]] = $elements[1];
            }

            return $result;
        }

        function array_implode( $glue, $separator, $array ) {
            if ( ! is_array( $array ) ) return $array;

            $string = array();

            foreach ( $array as $key => $val ) {
                if ( is_array( $val ) )
                    $val = implode( ',', $val );

                $string[] = "{$key}{$glue}{$val}";

            }
            return implode( $separator, $string );

        }
				
	    /*
	    
	      --------------------------------------------------------------
	    
	      ArrayAccess
	      
	      --------------------------------------------------------------
	    
	    */
	    
	    function offsetExists( $name )
	    {      
	      return isset( $this->_userData[ $name]);
	    }
	    
	    function offsetGet( $name)
	    {
	      	return isset( $this->_userData[ $name]) ? $this->_userData[ $name] : null;
	    }
	    
	    function offsetSet( $name, $value)
	    {
	    	$this->_userData[ $name] = $value;
	    	
	    	$this->Write( );
	    }
	    
	    function offsetUnset( $name){
			unset( $this->_userData[ $name]);
			
			$this->Write( );
		}
	}

?>