<?php
/*
	bounce Framework - Form upload object
	
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

	class FileUpload extends TextControl
	{
		public function __construct( $name)
		{
			parent::__construct( $name);		
		}
		
		public function Render( )
		{
			$outhtml = "<input type=\"file\" ";
			
			foreach( $this->_data as $key => $value)
				$outhtml .= "{$key}=\"{$value}\" ";
				
			$outhtml .= "/>\n";
			
			return $outhtml;
		}
	}
?>