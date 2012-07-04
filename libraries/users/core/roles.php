<?php
	class Roles
	{
		public static $SiteUser = 0;
		public static $Administrator = 1;
		public static $ViewUser = 2;
		public static $ReadOnly = 4;
		public static $Editor = 8;
        public static $Member = 16;
	}
	
	/*
	 * True administrator 1 + 2 + 4 + 8 = 15
	 * View Only Member 2 + 4 + 16 = 22
	 * True member 2 + 4 + 8 + 16 = 30
	 */

/*
 *
 SELECT u.Name,
   u.Role & member.Id > 0 AS IsMember,
   u.Role & admin.Id > 0 AS IsAdministrator,
   u.Role & editor.Id > 0 AS IsEditor,
   u.Role & readonly.Id > 0 AS IsReadOnly
   u.Role & view.Id > 0 AS IsViewUser
FROM Users u
JOIN Roles member ON member.Description =  'Member'
JOIN Roles admin ON admin.Description =  'Administrator'
JOIN Roles editor ON editor.Description =  'Editor'
JOIN Roles readonly ON readonly.Description = 'Read Only'
JOIN Roles view ON view.Description = 'View User'

 * INSERT INTO Roles (Id, Description )
 *  VALUES ( 0, 'Site User');
 */
?>