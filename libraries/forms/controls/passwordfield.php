<?php
	class PasswordField extends TextControl
	{
		public function Render( )
		{
			$outhtml = "<input type=\"password\" ";
			
			foreach( $this->_data as $key => $value)
				$outhtml .= "{$key}=\"{$value}\" ";
				
			$outhtml .= "/>";
			
			if( $this->_required)
				$outhtml .= "&nbsp;<span class=\"required\">*</span>\n";
			else
				$outhtml .= "\n";		
			
			return $outhtml;
		}
	}
?>