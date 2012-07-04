<?php
/*
 bounce Framework - Base Control class

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

abstract class Control implements ArrayAccess, IDisplayable
{
	protected $_data;
	protected $_value;
	protected $_required;
	protected $_validator;

	public function __construct( $name )
	{
		$this->_data[ "name"] = $name;
		$this->_required = false;
		$this->_validator = array( );
        $this->_data['value'] = isset( $_POST[$name]) && !empty($_POST[$name]) ? $_POST[$name] : null;
	}

	public function Required( $required )
	{
		$this->_required = $required;
	}
	
	public function Name() 
	{
		return $this->_data["name"];
	}

	public function AddValidator( IValidator $validator )
	{
		$attributes = $validator->GetAttribute( );
			
		if( $attributes != NULL )
		{
			if( is_array( $attributes ))
			{
				foreach( $attributes as $attr )
				{
					$this->_data[ $attr->Key( ) ] = $attr->Value( );
				}
			} 
			else
			{
				$this->_data[ $attributes->Key( ) ] = $attributes->Value( );
			}
		}
			
		$this->_validator[] = $validator;
	}

	public function IsRequired( )
	{
		return $this->_required;
	}

	public function Validator( )
	{
		return $this->_validator;
	}

	public function offsetExists( $field)
	{
		return isset( $this->_data[ $field]) ? true : false;
	}

	public function offsetGet( $field)
	{
		return $this->_data[ $field];
	}

	public function offsetSet( $field, $value)
	{
		$this->_data[ $field] = $value;
	}

	public function offsetUnset( $field)
	{
		unset( $this->_data[ $field]);
	}
}
?>
