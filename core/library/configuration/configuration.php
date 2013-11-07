<?php

interface IConfiguration
{
    static function Load($configurationFile);
    static function GetInstance();
}

abstract class Configuration implements IConfiguration
{
    protected $_configuration;
    protected static $_instance;

    public static function GetInstance() {
        return self::$_instance;
    }

    public static function GetSite()
    {
        return self::$_instance->_configuration->site;
    }

    public static function GetProfile()
    {
        $configuration = self::$_instance->_configuration;
        $profileName = $configuration->site->profile;

        foreach($configuration->profiles as $profile) {
            if( $profile->name == $profileName)
                return $profile;
        }

        return null;
    }

    public static function HasProperty($propertyName) {
        return property_exists(self::GetProfile(), $propertyName);
    }
}
