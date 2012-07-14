<?php
/*
	bounce framework - iconfiguration.php 
	
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
    Time: 7:18 AM    
*/
interface IConfiguration extends ArrayAccess
{
    function GetSitePath($configurationNode, $configurationElement );
    function Load($configurationFile);
}