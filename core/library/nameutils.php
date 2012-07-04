<?php

class NameUtils
{
	static function GetRealClassName( $classInstance ) 
	{
		$className = strtolower( get_class( $classInstance) );
			
		if( strpos($className, "\\") > 0) {
			$className = substr( $className, strrpos($className, "\\") + 1);
		}
			
		return $className;
	}
}

?>