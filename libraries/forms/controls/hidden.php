<?php
	 class Hidden extends Control
	 {	 	 	
	 	public function __construct( $name, $value )
	 	{
	 		parent::__construct( $name);
	 		
	 		$this->_data[ "value"] = $value;	
	 	}
	 	
	 	public function Render( )
	 	{
	 		$outhtml = "<input type=\"hidden\"";	 	
						
			foreach( $this->_data as $param => $value)
				$outhtml .= " {$param}=\"{$value}\"";
				
			$outhtml .= " />\n";
			
			return $outhtml;		
	 	}
	 }
?>