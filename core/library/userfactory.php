<?php
/*
 bounce Framework - userfactory.php

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
class UserFactory
{
    private static $_instance;
    private $_user;

    private function __construct() { }

    public static function &GetInstance( )
    {
        if( !isset( self::$_instance))
        {
            self::$_instance = new UserFactory();

            self::$_instance->GetUser();
        }

        return self::$_instance->_user;
    }

    private function GetUser() {
        if( !isset($this->_user)) {
            $this->_user = ThemedUser::GetInstance();
        }

        return $this->_user;
    }
}
