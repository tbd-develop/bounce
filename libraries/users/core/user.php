<?php
/*
	Bounce Framework - User 
	
    Copyright (C) 2013  Terry Burns-Dyson

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
        protected $_settings;
        protected $_model;
        protected $_database;
        protected $_session;
        protected $_loggedIn;
        protected $_id;
        protected $_role;
        protected $_login;

		public function __construct( )
		{
			$this->_id = 0;
			$this->_username = "";
			$this->_email = "";
			$this->_database = Database::Connection( );
			$this->_loggedIn = false;
			$this->_session = Session::GetInstance( );

			if($this->isAuthenticatedWithSession())
                $this->loginFromSession();
		}

		public function Login( $username, $password = "")
		{
            if( strlen( $username) > 0)
			{
				$query = "SELECT * FROM Users
				            WHERE Login = ? && Password = PASSWORD( ? )";

				$results = $this->_database->ExecuteQuery( $query, $username, $password);

				$user = $results->firstOrDefault();

				if( $user != null && $user->Active)
				{
                    $this->_model = new UserDTO($user->Id, $user->Login, $user->Role);

                    $this->_role = $user->Role;
                    $this->_id = $user->Id;
                    $this->_login = $user->Login;
					$this->_loggedIn = true;

					$session = Session::GetInstance( );

                    $userId = $user->Id;
					$sessionId = $session->GetSessionId( );

					$updateQuery = "UPDATE Users SET lastauthid = ?, lastlogin = NOW( ) WHERE id = ?";

					$this->_database->ExecuteQuery( $updateQuery, $sessionId, $user->Id );

					$session[ 'userid'] = $userId;
					$session[ 'lastauthid'] = $sessionId;

					$session->Write( );
				} else
					$this->_loggedIn = false;
			}

            return $this->_loggedIn;
		}

		public function Logout( )
		{
			unset( $this->_session[ "userid"]);
			unset( $this->_session[ "lastauthid"]);

            $this->_model = null;
            $this->_id = null;
            $this->_role = Roles::$SiteUser;
			$this->_loggedIn = false;
		}

		public function GetId( )
		{
			return $this->_id;
		}

		public function GetName( )
		{
			return $this->_login;
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

        public function setTheme($theme) {
            return $this->StoreSetting("theme", $theme);
        }

        public function getTheme() {
            if( !isset( $this->_settings))
                $this->GetSettings();

            return isset( $this->_settings["theme"]) ? $this->_settings["theme"] : "";
        }

        public function GetSettings() {
            $results = $this->_database->ExecuteQuery("SELECT * FROM UserSettings WHERE UserId = ?", $this->GetId());

            $settings = $results->singleOrDefault();

            if( isset($settings)){
                $this->_settings = json_decode($settings->Settings, true);
            }

            return $this->_settings;
        }

        public function GetSetting($name) {
            return isset($this->_settings[$name]) ? $this->_settings[$name] : "";
        }

        function getIpAddress()
        {
            if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
            {
                $ip=$_SERVER['HTTP_CLIENT_IP'];
            }
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
            {
                $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else
            {
                $ip=$_SERVER['REMOTE_ADDR'];
            }

            return $ip;
        }

        public function StoreSettings($newSettings)
        {
            $settingsQuery = $this->_database->ExecuteQuery( "SELECT * FROM UserSettings WHERE UserId = ?", $this->GetId());

            $settings = $settingsQuery->singleOrDefault();

            if( isset($settings)) {
                $currentSettings = $this->updateSettings( $settings->Settings, $newSettings);

                $settings = json_encode($currentSettings);

                $this->_database->ExecuteQuery("UPDATE UserSettings SET Settings = ? WHERE UserId = ?", $settings, $this->GetId());
            } else {
                $settings = json_encode($newSettings);

                $query = "INSERT INTO UserSettings ( UserId, Settings) VALUES ( ?, '${settings}' )";

                $this->_database->ExecuteQuery($query, $this->GetId());
            }

            $this->GetSettings();
        }

        public function StoreSetting($key, $value) {
            $this->StoreSettings(Array($key => $value));
        }

        public function updateSettings($settings, $toBeSet) {
            $currentSettings = json_decode($settings, true);

            foreach($toBeSet as $key => $value) {
                $found = false;
                $skipped = false;

                foreach( $currentSettings as $k => $currentValue) {
                    if( isset( $currentSettings [$key]))
                    {
                        if( $currentSettings[$key] != $value) {
                            $currentSettings[$key] = $value;
                            $found = true;
                            break;
                        } else
                        {
                            $skipped = true;
                            break;
                        }
                    }
                }

                if( $skipped)
                    continue;

                if( !$found) {
                    $currentSettings[$key] = $value;
                }
            }

            return $currentSettings;
        }

        protected function isAuthenticatedWithSession()
        {
            return isset($this->_session['userid']) && isset($this->_session['lastauthid']);
        }

        protected function loginFromSession()
        {
            $sessionId = $this->_session['lastauthid'];
            $userId = $this->_session['userid'];

            $loadQuery = "SELECT * FROM Users WHERE id = ${userId} AND lastauthid = '${sessionId}'";

            $result = $this->_database->ExecuteQuery($loadQuery);
            $user = $result->firstOrDefault();

            if ($user != null) {
                $this->_model = new UserDTO($user->Id, $user->Login, $user->Password, $user->Role);
                $this->_role = $user->Role;
                $this->_id = $user->Id;
                $this->_login = $user->Login;
                $this->_loggedIn = true;
            }
        }
    }
?>