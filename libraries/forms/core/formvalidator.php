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
class FormValidator
{
	private $_errors;
	private $_form;

	public function __construct( $form )
	{
		$this->_form = $form;
	}

	public function Validate( )
	{
		foreach( $this->_form->Controls( ) as $name => $controlArray )
		{
			$control = $controlArray[ "control"];
							
			if( $control->IsRequired( ))
			{
				if( !isset( $control['value']) || $control['value'] == '')
				{
					$this->_errors[] = $controlArray['label'] . " is a required field.";
				}
			}

			if( $control->Validator( ) != null )
			{				
				if( !is_array( $control->Validator( )))
				{
					if( isset( $control[ 'value'] ) &&
					!Validator::Valid( $control[ 'value'], $control->Validator( )))
					{
						$this->_errors[] = $controlArray[ 'label'] . ": " . $control->Validator( )->GetError( );
					}
				}
				else
				{
					foreach( $control->Validator( ) as $validator)
					{
						if( isset( $control[ 'value']) && !Validator::Valid( $control[ 'value'], $validator ))
						$this->_errors[] = $controlArray[ 'label'] . ": " . $validator->GetError( );
					}
				}
			}
		}
			
		return sizeof( $this->_errors) > 0 ? false : true;
	}

	public function Errors( )
	{
		return $this->_errors;
	}
}
?>