<?php
/*
	bounce Framework - Text Area control
	
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

	class Textarea extends Control
	{
		public function __construct( $name, $columns, $rows )
		{
			parent::__construct( $name);
			
			$this->_data[ "cols"] = $columns;
			$this->_data[ "rows"] = $rows;
		}
		
		public function Render( )
		{
			$outhtml = "<textarea ";
			
			foreach( $this->_data as $key => $value)
			{
				if( $key != "value")
					$outhtml .= "{$key}=\"{$value}\" ";
			}
				
			$outhtml .= ">\n";
			
			if( isset( $this->_data[ 'value']))
				$outhtml .= $this->_data[ 'value']; 
				
			$outhtml .= "</textarea>";
			
			if( $this->_required)
				$outhtml .= "&nbsp;<span class=\"required\">*</span>\n";
			else
				$outhtml .= "\n";		
			
			return $outhtml;
		}
	}
?>