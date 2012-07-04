<?php
/*
 bounce Framework - themeduser.php

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
class ThemedUser extends User
{
    protected $_firstName;
    protected $_lastName;

    protected $_settings;
    protected static $_instance;

    protected  function __construct()
    {
        parent::__construct();

        $this->getAdditionalInformation();
    }

    public static function &GetInstance( )
    {
        if( !isset( self::$_instance))
        {
            $c = __CLASS__;
            self::$_instance = new $c( );
        }

        return self::$_instance;
    }

    public function Login( $username, $password = "")
    {
        parent::Login($username, $password);

        $this->getAdditionalInformation();
    }

    private function getAdditionalInformation() {
        if( $this->IsLoggedIn() ) {
            $results = $this->_database->ExecuteQuery("SELECT FirstName, LastName FROM Users WHERE Id = ?", $this->_id);

            $user = $results->single();

            $this->_firstName = $user->FirstName;
            $this->_lastName = $user->LastName;
        }
    }

    public function GetName( )
    {
        return $this->_firstName . " " . $this->_lastName;
    }

    public function setTheme($theme) {
       return $this->StoreSetting("theme", $theme);
    }

    public function getTheme() {
        if( !isset( $this->_settings))
            $this->GetSettings();

        return isset( $this->_settings["theme"]) ? $this->_settings["theme"] : "";
    }

    public function GetSettings() {
        $results = $this->_database->ExecuteQuery("SELECT * FROM UserSettings WHERE UserId = ?", $this->GetId());

        $settings = $results->singleOrDefault();

        if( isset($settings)){
            $this->_settings = json_decode($settings->Settings, true);
        }
    }

    public function StoreSetting($key, $value) {
        $result = false;

        $results = $this->_database->ExecuteQuery("SELECT * FROM UserSettings WHERE UserId = ?", $this->GetId());

        $settings = $results->singleOrDefault();

        if( isset( $settings)) {
            $tmp = json_decode($settings->Settings, true);
            $found = false;
            $skipped = false;

            foreach( $tmp as $key => $setting) {
                if( isset( $setting[$key]))
                {
                    if( $setting[$key] != $value) {
                        $tmp[$key] = $value;
                        $found = true;
                        break;
                    } else
                    {
                        $skipped = true;
                        break;
                    }
                }
            }

            if( $skipped)
                return true;

            if( !$found) {
                $tmp[] = Array($key => $value);
            }

            $settings = json_encode($tmp);

            $this->_database->ExecuteQuery("UPDATE UserSettings SET Settings = ? WHERE UserId = ?", $settings, $this->GetId());

            $result = true;
        } else {
            $settings = json_encode(Array( $key => $value));

            $query = "INSERT INTO UserSettings ( UserId, Settings) VALUES ( ?, '${settings}' )";

            $this->_database->ExecuteQuery($query, $this->GetId());

            $result = true;
        }

        $this->GetSettings();

        return true;
    }
}

?>