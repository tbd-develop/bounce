-- Configure roles
INSERT INTO Roles (Id, Description )
 VALUES
( 0, 'Site User'),
(1, 'Administrator'),
(2, 'ViewUser'),
(4, 'Read Only'),
(8, 'Editor'),
(16, 'Member');

-- Function to allow extraction of most of course/event/contact data

CREATE FUNCTION SPLIT_STR(
  x VARCHAR(255),
  delim VARCHAR(12),
  pos INT
)
RETURNS VARCHAR(255)
RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos),
       LENGTH(SUBSTRING_INDEX(x, delim, pos -1)) + 1),
       delim, '');

--
-- Transfer User data
--
 INSERT INTO mortong_newdb.Users
( Name, Password, FirstName, LastName, Email, IsActive, DateCreated, Role )
SELECT UserName, UserPassword, UserRealName,
      UserSurname, UserEmail,
      UserEnabled, UserDateAdded,
      CASE WHEN UserEnabled THEN 31 ELSE 0 END
FROM mortong_maindb.users;

/*
 Transfer user contact data*/

 CREATE TABLE mortong_newdb.ContactJoin
(
  UserName VARCHAR(15),
  UserId int DEFAULT 0,
  PhoneNumber VARCHAR(30),
  MobileNumber VARCHAR(30),
  Address VARCHAR(30),
  Address2 VARCHAR(30),
  City VARCHAR(30),
  County VARCHAR(30),
  PostCode VARCHAR(10)
);

INSERT INTO mortong_newdb.ContactJoin
SELECT
  U.UserName,
  0,
  UAD.PhoneNumber,
  UAD.MobileNumber,
  SPLIT_STR(UAD.Address, '\r\n', 1),
  SPLIT_STR(UAD.Address, '\r\n', 2),
  SPLIT_STR(UAD.Address, '\r\n', 3),
  SPLIT_STR(UAD.Address, '\r\n', 4),
  SPLIT_STR(UAD.Address, '\r\n', 5)
FROM mortong_maindb.useradditional UAD
JOIN mortong_maindb.users U ON U.UserId = UAD.UserId
WHERE UAD.PhoneNumber <> '' OR UAD.MobileNumber <> '' OR
UAD.Address <> '';

UPDATE mortong_newdb.ContactJoin t
JOIN mortong_newdb.Users U ON U.Name = t.UserName
SET t.UserId = U.Id;

INSERT INTO mortong_newdb.Contacts
 ( Address, Address2 , City, County, Postcode, Phone, Mobile, IsPrimary )
 SELECT Address, Address2, City, County, PostCode, PhoneNumber, MobileNumber, 1
 FROM mortong_newdb.ContactJoin;

INSERT INTO mortong_newdb.UserContacts ( UserId, ContactId )
SELECT t.UserId, c.Id
FROM mortong_newdb.ContactJoin t
JOIN mortong_newdb.Contacts c
  ON c.Address = t.Address AND c.Phone = t.PhoneNumber AND
     c.Postcode = t.Postcode AND c.Mobile = t.MobileNumber;

DROP TABLE mortong_newdb.ContactJoin;

/*

Course/Event

*/

INSERT INTO mortong_newdb.Courses
 ( Name,
   Address,
   Address2,
   City,
   County,
   Postcode,
   Phone,
   Email,
   WebsiteURL)
SELECT
 CourseName,
 SPLIT_STR(CourseAddress, '\r\n', 1) AS Address,
 SPLIT_STR(CourseAddress, '\r\n', 2) AS Address2,
 SPLIT_STR(CourseAddress, '\r\n', 3) AS City,
 SPLIT_STR(CourseAddress, '\r\n', 4) AS County,
 SPLIT_STR(CourseAddress, '\r\n', 5) AS Postcode,
 CoursePhone,
 CourseEmail,
 CourseSiteAddress
FROM mortong_maindb.courses;

CREATE TABLE mortong_newdb.EventJoin
 ( Title VARCHAR(255),
    Description TEXT NULL,
    ScheduleDate DATE,
    IsSignupEnabled BIT(1),
    IsScored BIT(1),
    IsEnabled BIT(1),
    CourseId INT(10) NULL,
    CreatedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CourseKey VARCHAR(280)
 );

INSERT INTO mortong_newdb.EventJoin
  ( Title, Description, ScheduleDate, IsSignupEnabled,
      IsScored, IsEnabled, CreatedDate, CourseKey )
SELECT
 evtTitle, evtDescription, evtDate, evtSignup,
 evtScored, evtEnabled, evtDateAdded, c.CourseName + c.CoursePhone
FROM mortong_maindb.event e
LEFT JOIN mortong_maindb.courses c ON c.CourseId = e.evtScoreCourse;

UPDATE mortong_newdb.EventJoin t
JOIN mortong_newdb.Courses c ON t.CourseKey = c.Name + c.Phone
SET t.CourseId = c.Id;

INSERT INTO mortong_newdb.Events
( Title, Description, ScheduledDate, IsSignupEnabled, IsScored,
   IsEnabled, CourseId, CreatedDate )
SELECT Title, Description, ScheduleDate, IsSignupEnabled,
  IsScored, IsEnabled, CourseId, CreatedDate
FROM mortong_newdb.EventJoin;

DROP TABLE mortong_newdb.EventJoin;

/*
 Reviews
*/

CREATE TABLE mortong_newdb.ReviewJoin
 (
  ReviewTempId  INT(10) UNSIGNED AUTO_INCREMENT,
  CourseId INT(10) UNSIGNED NULL,
  Title VARCHAR(255),
  Content TEXT,
  Rating TINYINT(1),
  ValueForMoney TINYINT(1),
  WouldReturn BIT(1),
  DateVisited DATETIME,
  DatePosted DATETIME,
  AddUserId INT(10) UNSIGNED NULL,
  CourseKey VARCHAR(255),
  UserNameKey VARCHAR(255),
  PRIMARY KEY(`ReviewTempId`)
  );

INSERT INTO mortong_newdb.ReviewJoin
  ( Title, Content, Rating, ValueForMoney, WouldReturn,
    DateVisited, DatePosted, UserNameKey, CourseKey )
 SELECT
  ReviewTitle, ReviewText, ReviewRating,ReviewValueForMoney,ReviewReturn, ReviewDateVisited, ReviewDatePosted,
  u.UserName, c.CourseName + c.CoursePhone
 FROM mortong_maindb.reviews r
 LEFT JOIN mortong_maindb.courses c ON c.CourseId = r.ReviewCourse
 LEFT JOIN mortong_maindb.users u ON u.UserId = r.ReviewUserId;

 UPDATE mortong_newdb.ReviewJoin t
 JOIN mortong_newdb.Courses c ON t.CourseKey = c.Name + c.Phone
 JOIN mortong_newdb.Users u ON t.UserNameKey = u.Name
 SET CourseId = c.Id, AddUserId = u.Id;

 INSERT INTO mortong_newdb.Reviews
  ( CourseId, Title, Content, Rating, ValueForMoney, WouldReturn, DateVisited, DatePosted, AddUserId )
 SELECT CourseId, Title, Content, Rating, ValueForMoney, WouldReturn, DateVisited, DatePosted, AddUserId
 FROM mortong_newdb.ReviewJoin;

 DROP TABLE mortong_newdb.ReviewJoin;

 /*
  Event Signup copy
 */

 INSERT INTO mortong_newdb.EventSignups ( EventId, UserId, SignedUpOn )
SELECT 162, nu.Id, es.registered
FROM `eventsignups` es
JOIN users u ON u.UserId = es.userId
JOIN mortong_newdb.Users nu ON nu.Name = u.UserName
WHERE eventId = 73 AND
nu.Id NOT IN ( SELECT UserId FROM mortong_newdb.EventSignups )