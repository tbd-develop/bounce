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

	class LengthValidator extends BaseValidator
	{
		private $_minlength;
		private $_maxlength;
		
		public function __construct( $minlength = 0, $maxlength = 1, $clientSide = false )
		{
			$this->_minlength = $minlength;
			$this->_maxlength = $maxlength;
			$this->_error = "Length must be between ${minlength} and ${maxlength} characters.";
			$this->_attachedAttribute = Array( );

			if( $clientSide )
			{
				$this->_attachedAttribute[] = new KeyValuePair( "class", "validate-length");
			}
				
			$this->_attachedAttribute[] = new KeyValuePair( "maxlength", $maxlength );
		}
		
		public function Validate( $text )
		{
			$text = ltrim( rtrim( $text ));	
			 
			return ( strlen( $text ) >= $this->_minlength && strlen( $text ) <= $this->_maxlength );
		}
	}
?>