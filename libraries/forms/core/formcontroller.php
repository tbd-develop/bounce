<?php
/*
 bounce Framework - FormController extends base Controller for holding/managing POST and FILES

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

class FormController extends AuthController
{
	public $_files;

	public function __construct( )
	{
		parent::__construct( );	
			
		if( func_num_args( ) > 0)
		{
			$this->_arguments = func_get_args( 0);
		}		
		else
		{
			$this->_arguments = array( );
		}

		foreach( $_FILES as $key => $file)
		{
            if( is_array( $file['name'])) {
                $count = sizeof($file['name']);

                for($idx = 0; $idx < $count; $idx++) {
                    $keyValue = ltrim( rtrim( str_replace( "\\", "_", $key)));

                    $this->_files[ "${keyValue}_${idx}"] = new Upload( $file[ 'name'][$idx], $file[ 'tmp_name'][$idx], $file[ 'name'][$idx]);
                }
            } else
	            $this->_files[ ltrim( rtrim( str_replace( "\\", "_", $key)))] = new Upload( $file[ 'name'], $file[ 'tmp_name'], $file[ 'name']);
		}

		if( sizeof( $_POST) > 0)
		{
			foreach( $_POST as $key => $value)
			{
                if( !is_array($value))
                {
				    if( strlen( addslashes( $key)) == strlen( $key))
				    {
					    $this->_params[ ltrim(rtrim( str_replace( "\\", "_", $key)))] = addslashes( $value );
				    }
                } else {
                    $this->_params[ $key] = $value;
                }
			}
		}
				
		$this->_params[ "scripts"] = Array( "validation.js"); 		
	}
}
?>