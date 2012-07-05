<?php
/*
	bounce framework - install.php 
	
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
    
    Created with JetBrains PhpStorm
    Date: 7/4/12
    Time: 1:02 PM    
*/

class Install extends Controller
{
    public function index() {
        return $this->View( "default");
    }

    public function listdatabases() {
        $slash = DIRECTORY_SEPARATOR;
        $searchPath = ROOT_PATH . "{$slash}core{$slash}configuration";

        if( $directory = opendir($searchPath)) {
            $configurations = array();

            while( false !== ($entry = readdir($directory))) {
                if( preg_match("/-database.json/", $entry)) {
                    $fileContent = file_get_contents("{$searchPath}{$slash}{$entry}");

                    array_push($configurations, json_decode($fileContent));
                }
            }

            return $this->Json($configurations);
        }
    }

    public function createdatabase( DatabaseConfiguration $configuration) {
        $settings = json_encode($configuration);
        $slash = DIRECTORY_SEPARATOR;
        $filename = ROOT_PATH . "{$slash}core{$slash}configuration{$slash}{$configuration->profilename}-database.json";

        if( file_put_contents("{$filename}", $settings) > 0 ) {
            return $this->Json(json_decode($settings));
        }

        return $this->Json("FAIL");
    }

    public function createprofile(SiteProfile $profile) {
        return $this->Json("FAIL");
    }
}
