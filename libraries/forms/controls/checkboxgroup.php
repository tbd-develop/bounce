<?php
	class CheckBoxGroup extends Control
	{
		private $_options;		
		
		public function Render( )
		{
			$outhtml = "";
		 	
		 	foreach( $this->_options as $boxes)
		 		$outhtml .= $boxes->Render( );
		 		
		 	return $outhtml;
		}
		 
		public function AddBox( $value, $id = "", $label = "")
		{		 	
			 $checkbox = new CheckBox( "{$this->_data[ 'name']}[]", $value, $id, $label);
			 
			 if( is_array( $this->_value))
			 {
			 	if( in_array( $value, $this->_value))
			 		$checkbox->Checked = true;
			 }
			 
			 $this->_options[] = $checkbox;
		}
	}
?>