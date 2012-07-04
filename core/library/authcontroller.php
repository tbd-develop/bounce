<?php
/*
 bounce Framework - FormController extends base Controller for holding/managing POST and FILES

 Copyright (C) 2011  Terry Burns-Dyson

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
 
	class AuthController extends Controller
	{
		protected  $_requiredRole;
		protected $_user;
        protected $_database;
		
		public function __construct( )  
		{			
			parent::__construct( );
			
			$this->_user = UserFactory::GetInstance( );
            $this->_database = Database::Connection( );

			$this->_params[ 'user'] = $this->_user;
		}		
		
		public function RequiredRole( $requiredRole = null )
		{
			if( $requiredRole != null )
			{
				$this->_requiredRole = $requiredRole;	
			}
				
			return $this->_requiredRole;
		}

        public function GetConfiguredRoles($userId = 0) {
            $results = $this->_database->ExecuteQuery(
                "SELECT
                    u.Role & member.Id > 0 AS IsMember,
                    u.Role & admin.Id > 0 AS IsAdministrator,
                    u.Role & editor.Id > 0 AS IsEditor,
                    u.Role & readonly.Id > 0 AS IsReadOnly,
                    u.Role & viewonly.Id > 0 AS IsViewUser
                FROM Users u
                JOIN Roles member ON member.Description =  'Member'
                JOIN Roles admin ON admin.Description =  'Administrator'
                JOIN Roles editor ON editor.Description =  'Editor'
                JOIN Roles readonly ON readonly.Description = 'Read Only'
                JOIN Roles viewonly ON viewonly.Description = 'View User'
                WHERE u.Id = ?", isset($userId) ? $userId : $this->_user->GetId( ));

          return $results->first();
        }
	}
?>
