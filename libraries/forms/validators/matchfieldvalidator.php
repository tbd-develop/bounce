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

	class MatchFieldValidator extends BaseValidator
	{
		private $_control;
		
		public function __construct( $control, $error = null )
		{
			$this->_control = $control;		
			
			$this->_error = isset( $error ) ? $error : "Values in fields must match.";
		}
		
		public function Validate( $text )
		{			
			return isset( $this->_control[ 'value']) ? $text == $this->_control[ 'value'] : false;
		}
	}
?>