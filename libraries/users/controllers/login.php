<?php
	class Login extends FormController
	{	
		public function index( )
		{
			$fail = false;
			
			if( isset( $this->_params[ 'username']))
			{
				$fail = $this->_user->Login( $this->_params[ 'username'], $this->_params[ 'userpass']);
			} 
			else
			{
				$fail = true;
			}
				
			if( $fail)
			{
				$this->_params[ "pageTitle"] = "Invalid Login";
				$this->_params[ "error"] = "Invalid username and/or password.";
				
				$this->Load->View( "error", "index", $this->_params);
			}
			else
			{
				$this->_params[ "user"] = $this->_user;	
				
				$this->Load->View( strtolower( get_class( $this)), "index", $this->_params);
			}						
		}		
	}
?>