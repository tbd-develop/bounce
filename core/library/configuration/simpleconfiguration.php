<?php
/*
	bounce framework - simpleconfiguration.php 
	
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
    Date: 7/5/12
    Time: 6:01 AM
*/
class SimpleConfiguration
{
    private $_configuration;
    private $_filePath;
    private static $_instance;

    private function __construct($configurationFile) {
        $this->_filePath = $configurationFile;

        $this->_configuration = simplexml_load_file($configurationFile);
    }

    public function getConfiguration(){
        return $this->_configuration;
    }

    public function Save() {
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($this->_configuration->asXML());

        if(file_exists( $this->_filePath)) {
            unlink($this->_filePath);
        }

        $dom->save($this->_filePath);
    }

    public function HasSetting($nodeName) {
        return isset( $this->_configuration->$nodeName);
    }

    public function GetSetting($nodeName, $keyValue, $default = "") {
        foreach($this->_configuration->$nodeName->key as $key) {
            if( $key['name'] == $keyValue)
                return $key['value']->__toString();
        }

        return $default;
    }

    public function GetSettingsCollection($nodeName) {
        $result = array();

        foreach($this->_configuration->$nodeName->key as $setting) {
            $name = $setting['name'];
            $value = $setting['value']->__toString();

            $result["${name}"] = $value;
        }

        return $result;
    }

    public static function Load($configurationFile)
    {
        if( !isset( self::$_instance))
            self::$_instance = new SimpleConfiguration( $configurationFile );

        return self::$_instance;
    }

    public static function &GetInstance()
    {
        if( !isset( self::$_instance))
            self::$_instance = new SimpleConfiguration( "configuration.xml");

        return self::$_instance;
    }
}