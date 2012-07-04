<?php
	class SubmitButton extends Button
	{
		private $submit = "Submit";
		
		public function __construct( $name, $submitText) 
		{
			parent::__construct( $name);
			
			$this->submit = !empty($submitText) ? $submitText : $this->submit;
		}
		
		public function Render( )
		{
			$outhtml = "<button type=\"submit\"";
			
			foreach( $this->_data as $param => $value)
				$outhtml .= " {$param}=\"{$value}\"";
				
			$outhtml .= ">{$this->submit}</button>\n";
			
			return $outhtml;
		}
	}
?>
