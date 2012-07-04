<?php
/*
	bounce Framework - Basic Form obect for posting data
	
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

	class Form implements IForm
	{
		private $_formInfo;
		private $_formControls;
		private $_formButtons;
		private $_sort;
		
		public function __construct( $name, $action,  $id = null,$method = "post", $sort = "ElementSort::SortNone")
		{
			$this->_formInfo[ "name"] = $name;
			$this->_formInfo[ "action"] = $action;
			$this->_formInfo[ "method"] = $method;
			
			if( $id) {
				$this->_formInfo['id'] = $id;
			}
			
			$this->_formControls = array( );
			$this->_formButtons = array( );					
						
			$this->_sort = $sort;
		}
		
		public function RenderForm($rowTemplate, $buttonPanel) 
		{
			$outHtml = $this->StartForm( );
			
			foreach( $this->Controls() as $key => $value) {
				$control = $value["control"];
												
				$markup = $this->DrawLabel( $control->Name());
				$markup .= $this->DrawControl( $control->Name());
				
				if( $rowTemplate > '') 
				{
					$outHtml .= sprintf($rowTemplate, $markup);
				} else 
				{
					$outHtml .= $markup;	
				}
			}
			
			$buttons = "";
					
			foreach( $this->_formButtons as $key => $value) 
			{
				$control = $value["control"];
				
				$buttons .= $this->DrawButton( $control->Name());
			}
			
			$outHtml .= sprintf( $buttonPanel, $buttons);
			
			$outHtml .= $this->EndForm( );
			
			return $outHtml;
		}
		
		public function AddControl( Control $control, $label = "", $group = "" )
		{
			if( is_a( $control, "FileUpload"))
				$this->_formInfo[ 'enctype'] = "multipart/form-data";
				
			$this->_formControls[ $control[ 'name']] = array( "control" => $control, "label" => $label, "group" => $group);
		}
		
		public function AddButton( Button $button, $group = "")
		{
			$this->_formButtons[ $button[ 'name']] = array( "control" => $button, "group" => $group);
		}
		
		public function Controls( )
		{
			return $this->_formControls;
		}
		
		public function StartForm( )
		{
			$outhtml = "<form ";
			
			foreach( $this->_formInfo as $param => $value)
				$outhtml .= "{$param}=\"{$value}\" ";			
			
			$outhtml = rtrim( $outhtml) . ">\n";
			
			return $outhtml;
		}
		
		public function EndForm( )
		{
			$outhtml = "";	
			
			foreach( $this->_formControls as $object)
			{ 
				if( $object[ 'control'] instanceof Hidden)
					$outhtml .= $object[ 'control']->Render( );
			}
			
			$outhtml .= "\n</form>";
			
			return $outhtml;
		}
		
		public function DrawLabel( $name )
		{
			$outhtml = ""; 
			$element = $this->_formControls[ $name];
			
			if( isset( $element['label'] ))
			{			
				if( isset( $element[ 'control'][ 'id']))
				{
					$id = $element[ 'control'][ 'id'];
				}
				else 
				{
					$id = "{$element[ 'control']['name']}id";
					$element[ 'control'][ 'id'] = $id;
				}
			
				$outhtml = "<label for=\"{$id}\">{$element['label']}</label>";
			}
							
			return $outhtml;
		}
		
		public function DrawControl( $name )
		{
			$element = $this->_formControls[ $name];
			
			return $element['control']->Render( );
		}
		
		public function DrawButton( $name)
		{
			$button = $this->_formButtons[ $name];
			
			return $button[ 'control']->Render( );
		}
	}
?>
