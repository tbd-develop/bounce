<?php
/*
	bounce Framework - Radiogroup collection of RadioButtons
	
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

	class Radiogroup extends Control
	{
		private $_options;
		private $_buttonAttributes;
		
		public function __construct($name) 
		{
			parent::__construct($name);
			
			$this->_buttonAttributes = Array();
		}		
		
		 public function Render( )
		 {
		 	$outhtml = "<ul class=\"radiogroup\">";
		 	
		 	foreach( $this->_options as $button)
		 	{
		 		$outhtml .= "<li>" . $button->Render( ) . "</li>";
		 	}
		 		
		 	$outhtml .= "</ul>\n";
		 	
		 	return $outhtml;
		 }
		 
		 public function SetButtonAttribute( $attrName, $value) {
		 	$this->_buttonAttributes[$attrName] = $value;
		 }
		 
		 public function AddButton( $value, $id = "", $label = "")
		 {		 	
		 	$button = new RadioButton( $this->_data[ 'name'], $value, $id, $label);
			
			foreach( $this->_buttonAttributes as $attr => $value) {
				$button[$attr] = $value;
			}
		 	
		 	if( $value == $this->_value)
		 		$button->Checked = true;
		 	
		 	$this->_options[] = $button;
		 }
	}
?>