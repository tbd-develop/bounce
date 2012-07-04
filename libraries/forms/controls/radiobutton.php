<?php
/*
	bounce Framework - RadioButton Control
	
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

	class RadioButton extends Control
	{
		public $Checked;
		private $_label;		
		
		public function __construct( $name, $value, $id = "", $label = "")
		{
			parent::__construct( $name);
			!empty( $id) ? $this->_data[ 'id'] = $id : null;
			$this->_label = $label;				
			$this->_data[ 'value'] = $value;
			$this->Checked = false;
		}
		
		public function Render( )
		{
			$outhtml = "";
			
			if( !empty( $this->_label))
			{
				if( !isset( $this->_data[ 'id']) || empty( $this->_data[ 'id']))
					$id = $this->_data[ 'name'] . "id";
				else
					$id = $this->_data[ 'id'];
				
				$outhtml .= "<label class=\"radiobutton\" for=\"{$id}\">" . $this->_label . "</label>\n";
			}
				 
			$outhtml .= "<input type=\"radio\"";		
			
			foreach( $this->_data as $key => $value)
				$outhtml .= "{$key}=\"{$value}\" ";	
				
			if( $this->Checked)
				$outhtml .= " checked";
				
			$outhtml .= " />";
						
			return $outhtml;
		}	
	}
?>