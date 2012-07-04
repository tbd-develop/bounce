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

	class Captcha extends Controller
	{	
		public function load( )
		{				
			$session = Session::GetInstance();
			$resource = ROOT_PATH . $this->_configuration[ 'directories'][ 'resource'];
				
			$imageFileResource = "${resource}/images/img.png";
			$RandomStr = md5(microtime( ) ); 
			$verifystr = substr($RandomStr, 0 , 7 ); 
			
			$image = imagecreatefrompng( $imageFileResource );
			$imagesize = getimagesize( $imageFileResource );			
			
			$fillColor = imagecolorallocate( $image, 145, 164, 43 );	
			$textcolour = imagecolorallocate( $image, 0, 0, 0);
			$LineColor = imagecolorallocate($image, 255, 255, 255 ); 
			$LineColor2 = imagecolorallocate( $image, 150, 180, 109);
			$LineColor3 = imagecolorallocate( $image, 99, 99, 99);	
			
			imageline($image, rand( 0, 5), rand( 0, 10), rand(20, $imagesize[1]), rand( 20, $imagesize[1]), $LineColor); 
			imageline($image, rand( 10, 25), rand( 80, 100), rand($imagesize[0],$imagesize[1]), rand( 20, 30), $LineColor); 
			imageline($image, rand(0, 5), rand( 1, 10), rand($imagesize[0], $imagesize[1]), rand( 60, 80), $LineColor2);
			imageline($image, rand( 20, 50), rand( 1, 10), rand( 20, 40), rand( 50, 100), $LineColor3);
			
			$offset = 5;
			
			
			for( $idx = 0; $idx < strlen( $verifystr ); $idx++ )
			{
				$fontsize = rand( 3, 7);
				
				imagestring( $image, $fontsize, $offset, rand( 5, $imagesize[1] - 20), $verifystr[$idx], $textcolour );

				$offset += rand(5 + imagefontwidth( $fontsize ), $imagesize[0] / strlen( $verifystr));
			}

			
			$_SESSION[ 'key'] = md5( $verifystr );
			
			header( 'Content-type: image/png');
			
			imagepng( $image);
		}
	}
?>