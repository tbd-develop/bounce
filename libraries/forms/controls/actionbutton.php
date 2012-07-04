<?php
	class ActionButton extends Button
	{
		private $action = "Button";
		
		public function __construct( $name, $buttonText) 
		{
			parent::__construct( $name);
			
			$this->action = !empty($buttonText) ? $buttonText : $this->action;
		}
		
		public function Render( )
		{
			$outhtml = "<button type=\"button\"";
			
			foreach( $this->_data as $param => $value)
				$outhtml .= " {$param}=\"{$value}\"";
				
			$outhtml .= ">{$this->action}</button>\n";
			
			return $outhtml;
		}
	}
?>
