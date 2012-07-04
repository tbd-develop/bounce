<?php
	class JsTextArea extends Textarea
	{
		var $_toolStrip;
		var $_width;
		var $_height;
		var $_saveMethod;
		
		public function SetToolbar( $bar )
		{
			$this->_toolStrip = $bar;
		}
		
		public function Size( $width, $height )
		{
			$this->_width = $width;
			$this->_height = $height;
		}
		
		public function Save( $saveMethod )
		{
			$this->_saveMethod = $saveMethod;
		}
		
		public function Render( )
		{
			$outputHtml = parent::Render( );			

			if( isset( $this->_data[ 'id']))
			{
				$id = $this->_data[ 'id'];
				$width = $this->_width;
				$height = $this->_height;
				
				$toolBar = $this->_toolStrip;
				
				$outputHtml .= "<script type=\"text/javascript\">
								//<![CDATA[
								tinyMCE.init({
    							theme : 'advanced',
    							mode: 'exact',
    							elements : '${id}',
								width: '${width}', 
								height: '${height}',
								inline_styles : false";
								
				if( strlen( $this->_saveMethod ) > 0 )
				{
					$outputHtml .= ",
									plugins: 'save', 
							    	theme_advanced_buttons3_add : 'save', 
    								save_onsavecallback : '{$this->_saveMethod}'";
				}
				
				$outputHtml .= "});								
								//]]>
								</script>";
			}
			
			return $outputHtml;
		}
	}
?>