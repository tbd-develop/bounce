<?php

class JsonConfiguration extends Configuration
{
    function __construct($configurationFilePath)
    {
        $json = file_get_contents($configurationFilePath);

        $this->_configuration = json_decode($json);
    }

    static function Load($configurationFile)
    {
        if( !isset( self::$_instance))
            self::$_instance = new JsonConfiguration( $configurationFile );
    }

    static function GetInstance()
    {
        return self::$_instance->_configuration;
    }
}