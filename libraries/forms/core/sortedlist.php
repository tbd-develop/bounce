<?php
/*
	bounce Framework - Sorted list for use in form elements
	
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

	class SortedList implements IList
	{
		private $_elements;
		
		public function __construct( $contents = null)
		{
			if( is_array( $contents))
				$this->_elements = $contents;
			else
				$this->_elements = array( );
		}
		
		public function Add( $key, $value)
		{
			if( !array_key_exists( $key, $this->_elements))
			{
				$this->_elements[ $key] = new ListItem( $key, $value);
				
				uasort( $this->_elements, array( $this, "sortValue"));
			}
		}
		
		private function sortValue( $a, $b)
		{
			$sortable = array( $a->Value, $b->Value);
			$sorted = $sortable;

			sort( $sorted);
			
			return ( $sorted[ 0] == $sortable[0]) ? - 1 : 1; 
		}
		
		public function Sort( )
		{
			uasort( $this->_elements, array( $this, "sortValue"));
		}
		
		public function Count( )
		{
			return sizeof( $this->_elements);
		}
		
		public function rewind( )
		{
			reset( $this->_elements);
		}
		
		public function current( )
		{
			return current( $this->_elements);
		}
		
		public function key( )
		{
			return key( $this->_elements);
		}
		
		public function next( )
		{
			return next( $this->_elements);
		}
		
		public function valid( )
		{
			return $this->current( ) !== false;
		}
	}
?>