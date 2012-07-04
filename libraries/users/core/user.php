<?php
/*
	bounce Framework - User
	
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

	class User implements IUser
	{
		protected $_id;
        protected $_username;
        protected $_email;
        protected $_role;
        protected $_database;
        protected $_session;
        protected $_loggedIn;

        protected static $_instance;

		protected function __construct( )
		{
			$this->_id = 0;
			$this->_username = "";
			$this->_email = "";
			$this->_database = Database::Connection( );
			$this->_loggedIn = false;
			$usertable = UsersConfig::Object( "usertable");
			$this->_session = Session::GetInstance( );

			if( isset( $this->_database) &&
                isset( $this->_session[ 'userid']) &&
                isset( $this->_session[ 'lastauthid']))
			{
				$sessionId = $this->_session[ 'lastauthid'];
				$userId = $this->_session[ 'userid'];

				$loadQuery = "SELECT * FROM ${usertable} WHERE id = ${userId} AND lastauthid = '${sessionId}'";

				$result = $this->_database->ExecuteQuery( $loadQuery );
				$user = $result->firstOrDefault( );

				if( $user != null )
				{
					$this->_id = $user->Id;
					$this->_username = $user->Name;
					$this->_email = $user->Email;
					$this->_role = $user->Role;
					$this->_loggedIn = true;
				}
			}
		}

		public static function &GetInstance( )
		{
        	if( !isset( self::$_instance))
        	{
        		$c = __CLASS__;
          		self::$_instance = new $c( );
        	}

        	return self::$_instance;
		}

		public function Login( $username, $password = "")
		{
			if( strlen( $username) > 0)
			{
				$usertable = UsersConfig::Object( "usertable");

				$query = "SELECT Id, Name, Email, Role, IsActive FROM {$usertable}
				            WHERE Name = '{$username}' && Password = PASSWORD( '{$password}')";

				$results = $this->_database->ExecuteQuery( $query);

				$user = $results->firstOrDefault();

				if( $user != null && $user->IsActive)
				{
					$this->_username = $user->Name;
					$this->_email = $user->Email;
					$this->_role = $user->Role;
					$this->_loggedIn = true;
                    $this->_id = $user->Id;

					$userId = $user->Id;
					$session = Session::GetInstance( );

					$sessionId = $session->GetSessionId( );

					$updateQuery = "UPDATE ${usertable} SET lastauthid = '${sessionId}', lastlogin = NOW( ) WHERE id = ${userId}";

					$this->_database->ExecuteQuery( $updateQuery );

					$session[ 'userid'] = $userId;
					$session[ 'lastauthid'] = $sessionId;

					$session->Write( );
				} else
					$this->_loggedIn = false;
			}
		}

		public function Logout( )
		{
			unset( $this->_session[ "userid"]);
			unset( $this->_session[ "lastauthid"]);

			$this->_username = "";
			$this->_email = "";
			$this->_loggedIn = false;
		}

		public function GetId( )
		{
			return $this->_id;
		}

		public function GetName( )
		{
			return $this->_username;
		}

        public function MyRole() {
            return $this->_role;
        }

		public function IsPermitted( $requiredRole )
		{
			if( !( $this->_role & $requiredRole))
				return false;

			return true;
		}

		public function GetEmail( )
		{
			return $this->_email;
		}

		public function IsLoggedIn( )
		{
			return $this->_loggedIn;
		}
	}
?>