DROP DATABASE IF EXISTS {DBNAME};
CREATE DATABASE {DBNAME};
USE {DBNAME};

#
# Table structure for table 'emailtemplates'
#

DROP TABLE IF EXISTS emailtemplates;
CREATE TABLE `emailtemplates` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Filename` varchar(255) NOT NULL,
  `Content` text NOT NULL,
  `DateCreated` datetime NOT NULL,
  `Current` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# Table structure for table 'posts'
#

DROP TABLE IF EXISTS posts;
CREATE TABLE `posts` (
  `PostId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Title` varchar(100) NOT NULL,
  `Content` text NOT NULL,
  `DateAdded` datetime NOT NULL,
  `UserId` int(11) NOT NULL,
  `DateEdited` datetime DEFAULT NULL,
  `EditedBy` int(11) DEFAULT NULL,
  `Public` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`PostId`),
  FULLTEXT KEY `FtIdxContent` (`Content`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

#
# Table structure for table 'profile'
#

DROP TABLE IF EXISTS profile;
CREATE TABLE `profile` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) unsigned NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `msnid` varchar(255) DEFAULT NULL,
  `yahooid` varchar(255) DEFAULT NULL,
  `aimid` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `aboutme` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

#
# Table structure for table 'role'
#

DROP TABLE IF EXISTS role;
CREATE TABLE `role` (
  `id` mediumint(8) unsigned NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Table structure for table 'user'
#

DROP TABLE IF EXISTS user;
CREATE TABLE `user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT '0',
  `lastauthid` varchar(255) DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `auth` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/* Basic Roles */
INSERT INTO `role` ( id, description ) VALUES ( 0, "Site User");
INSERT INTO `role` ( id, description ) VALUES ( 1, "Administrator");
INSERT INTO `role` ( id, description ) VALUES ( 2, "User View");
INSERT INTO `role` ( id, description ) VALUES ( 4, "Read Only");
INSERT INTO `role` ( id, description ) VALUES ( 8, "Editor");

/* Default Users */
INSERT INTO `user` ( NAME, PASSWORD, role ) VALUES ( 'user', PASSWORD( ''), 4 );
INSERT INTO `profile` ( userid ) VALUES ( LAST_INSERT_ID());

INSERT INTO `user` ( NAME, PASSWORD, role ) VALUES ( 'site', PASSWORD( 'site'), 6);
INSERT INTO `profile` ( userid ) VALUES ( LAST_INSERT_ID());

INSERT INTO `user` ( NAME, PASSWORD, role ) VALUES ( 'admin', PASSWORD( 't3mpP@5s'), 7 );
INSERT INTO `profile` ( userid ) VALUES ( LAST_INSERT_ID());