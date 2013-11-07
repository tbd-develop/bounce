<?php
	class Login extends FormController
	{	
		public function index( )
		{
            $profile = $this->_configuration->GetProfile();

            if( $this->_user != null &&
                $this->_user->IsLoggedIn() )
                return $this->Redirect($profile->redirectOnLogin != null ? $profile->redirectOnLogin : "/");

            if( isset( $this->_params['login']))
			{
                if( !$this->_user->Login($this->_params['login'], $this->_params['pass']))
                {
                    $this->_params[ "pageTitle"] = "Invalid Login";
                    $this->_params[ "error"] = "Invalid username and/or password.";

                    return $this->View( "index", $this->_params, "login_pagebody");
                } else
                    return $this->Redirect($profile->redirectOnLogin != null ? $profile->redirectOnLogin : "/");
			}
			else
			{
				$this->_params[ "user"] = $this->_user;	
				
				return $this->View( "index", $this->_params, "login_pagebody");
			}						
		}

        public function logout() {
            $this->_user->Logout();

            return $this->Redirect("/");
        }
	}
?>