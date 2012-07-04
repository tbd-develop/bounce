<?php
/*
	bounce Framework - Upload File management
	
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

	class Upload
	{
		private $_temppath;
		private $_savepath;	
		private $_name;
	
		public function __construct( $name, $temppath, $savepath )
		{			
			$this->_name = $name;
			$this->_temppath = $temppath;
			$this->_savepath = $savepath;
		}
		
		public function Name( )
		{
			return $this->_name;
		}
		
		public function Save( $savepath )
		{
			$this->SaveWithName($savepath, $this->_name);
		}

        public function SaveWithName( $savepath, $name )
        {
            return move_uploaded_file( $this->_temppath, $savepath . DIRECTORY_SEPARATOR . $name);
        }
	}
?>