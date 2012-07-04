<?php

class KeyValuePair
{
 	private $_key;
	private $_value;
	
	public function __construct( $key, $value )
	{
		$this->_key = $key;
		$this->_value = $value;
	}
	
	public function Key( )
	{
		return $this->_key;
	}
	
	public function Value( )
	{
		return $this->_value;
	}
}

?>