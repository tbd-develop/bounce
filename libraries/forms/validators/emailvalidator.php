<?php
/*
	bounce Framework - Basic Validator class, accepts a string value and an IValidator to use for validation, returns bool
	
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

	class EmailValidator extends BaseValidator
	{
		public function __construct( )
		{
			$this->_attachedAttribute = Array( );
			
			$this->_attachedAttribute[] = new KeyValuePair( 'email', "true");
		}
		
		public function Validate( $text )
		{			
			if( !preg_match( "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $text) > 0)
		    	return false;

			return true;	
		}	
	}
?>