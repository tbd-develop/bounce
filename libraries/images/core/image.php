<?php
	/*
	bounce Framework - Session management
	
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

	class Image extends Controller
	{
		public function load( )
		{
			if( sizeof( $this->_arguments) > 0 )
			{
				$imageFile = $this->_arguments[0];
		
				$template = $this->_configuration[ 'configuration']['default_template'];
				$template = ROOT_PATH . $this->_configuration[ 'directories'][ 'templates'] . "/" . $template;
				$resource = ROOT_PATH . $this->_configuration[ 'directories'][ 'resource'];
		
				$imageFileTemplate = "${template}/images/${imageFile}";
				$imageFileResource = "${resource}/images/${imageFile}";
				$imageToLoad = "";
				
				if( file_exists( $imageFileTemplate ))
					$imageToLoad = $imageFileTemplate;
				else if( file_exists( $imageFileResource))		
					$imageToLoad = $imageFileResource;					
		
				if( strlen( $imageToLoad) > 0)
				{
					$fileType = StringFunctions::FileExtension( $imageToLoad );
					$image = null;
					$header = "";
			
					switch( $fileType)
					{
						case "png":						
							$image = imagecreatefrompng( $imageToLoad);
							imagealphablending($image, true); // setting alpha blending on
							imagesavealpha($image, true); // save alphablending setting (important)
							$header = "Content-type: image/png";
							break;
						case "jpg":
							$image = imagecreatefromjpeg( $imageToLoad);
							$header = "Content-type: image/jpeg";
							break;				
					}
					
				header( $header);
			
				if( $fileType == "png")
					imagepng( $image);
				
				if( $fileType == "jpg")
					imagejpeg( $image);
				}
			}
		}
	}	
?>