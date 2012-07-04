<?php
/*
	bounce Framework - Multiselect control for Forms
	
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

	class MultiSelect extends ComboBox
	{
		public function __construct( $name, IList $list = null, $size = 5)
		{
			parent::__construct( $name, $list);
			$this->_data[ 'size'] = $size;			
		}
		
		public function Render( )
		{
			$outhtml = "<select multiple";
			
			foreach( $this->_data as $key => $value)
				$outhtml .= " {$key}=\"{$value}\"";
			
			$outhtml .= ">\n";
			
			foreach( $this->_items as $item)
			{
				$outhtml .= "<option value=\"{$item->Value}\"";
				
				if( $item->Value == $this->_value)
					$outhtml .= " selected";
				
				$outhtml .= ">{$item->Description}</option>\n";
			}
				
			$outhtml .= "</select>\n";
			
			return $outhtml;
		}
	}
?>