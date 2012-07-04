<?php
/*
	bounce Framework - Sorting routines for Form Elements
	
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

	class ElementSort
	{
		public static function SortNone( &$elements) {  }
		
		public static function SortLabels( &$elements) 
		{ 
			foreach( $elements as $key => $value)
				$labels[ $key] = $value[ 'label'];
				
			array_multisort( $labels, SORT_ASC, SORT_STRING, $elements);	
		}
		
		public static function SortGroups( &$elements) 
		{ 
			foreach( $elements as $key => $value)
				$groups[ $key] = $value[ 'group'];

			array_multisort( $groups, SORT_ASC, SORT_STRING, $elements);
		}
		
		public static function SortLabelsGroups( &$elements) 
		{ 
			foreach( $elements as $key => $value)
			{
				$labels[ $key] = $value[ 'label'];
				$groups[ $key] = $value[ 'group'];
			}
			
			array_multisort( $labels, SORT_ASC, SORT_STRING, 
							  $groups, SORT_ASC, SORT_STRING, $elements);
		}
		
		public static function SortGroupsLabels( &$elements) 
		{ 
			foreach( $elements as $key => $value)
			{
				$labels[ $key] = $value[ 'label'];
				$groups[ $key] = $value[ 'group'];
			}
			
			array_multisort( $groups, SORT_ASC, SORT_STRING, 
							  $labels, SORT_ASC, SORT_STRING, $elements);
		}		
	}
?>