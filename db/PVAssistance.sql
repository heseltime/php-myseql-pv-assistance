CREATE TABLE user (
	userId int(11) NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (userId),
	firstName VARCHAR(50),
    lastName VARCHAR(50),
    sex VARCHAR(10),
    dateOfBirth DATE,
    emailAddress VARCHAR(50),
    phoneNo VARCHAR(20)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 CHARSET=utf8;;

INSERT INTO `user` (`userId`, `firstName`, `lastName`, `sex`, `dateOfBirth`, `emailAddress`, `phoneNo`) VALUES
(1, 'Jack', 'Heseltine', 'm', '2023-06-08', 'jack.heseltine@gmail.com', '436649653008');

CREATE TABLE application (
    id NUMERIC(20) NOT NULL,
	PRIMARY KEY (id),
	userId int(11) NOT NULL,
    address VARCHAR(255),
    outputInKWP FLOAT,
    constructionDate DATE,    
    PVType VARCHAR(20),
    requestDate DATETIME,
    IPAddress VARCHAR(32),
	token VARCHAR(20),
	uuid VARCHAR(100),
	status ENUM('In Progress', 'Approved', 'Rejected'),
	notes VARCHAR(200),
	KEY userId (userId)
  ) ENGINE=InnoDB CHARSET=utf8;;

INSERT INTO `application` (`id`, `userId`, `address`, `outputInKWP`, `constructionDate`, `PVType`, `requestDate`, `IPAddress`, `token`, `uuid`, `status`, `notes`) VALUES
(12345678912345675555, 1, 'OTTENSHEIMERSTRASSE 68', 0.1, '2023-06-09', 'business', '2023-06-13 20:29:38', '172.19.0.6', 'g8DNq', '259354fb-4eef-490f-b9dd-f9667785e702', 'Approved', ''),
(12345678912345678889, 1, 'OTTENSHEIMERSTRASSE 68', 0.05, '2023-06-17', 'business', '2023-06-13 17:19:41', '172.19.0.6', 'ZC12M', '5a834ff7-43be-4a90-b26b-982522b02092', 'Rejected', 'This application required further review.'),
(12345678912345678888, 1, 'OTTENSHEIMERSTRASSE 68', 0.1, '2023-06-14', 'business', '2023-06-13 17:11:41', '172.19.0.6', 'ewTS8', 'checkStatus487c9491-7fdc-4d40-ac20-4689b61fe197', 'In Progress', ''),
(12345678912345677779, 1, 'OTTENSHEIMERSTRASSE 68', 0.1, '2023-06-17', 'business', '2023-06-13 15:43:26', '172.19.0.6', '^*Fjg', 'checkStatus175dd8b2-7d7d-440c-8c2c-8a7f39054e8912345678912345677779', 'Rejected', 'Problem'),
(12345678912345677778, 1, 'OTTENSHEIMERSTRASSE 68', 0.1, '2023-06-17', 'business', '2023-06-13 15:01:33', '172.19.0.6', 'duJ7!', 'checkStatus4643f918-7b8e-48ac-bf73-e3c6d7703ef012345678912345677778', 'Approved', ''),
(12345678912345677777, 1, 'OTTENSHEIMERSTRASSE 68', 0.1, '2023-06-17', 'business', '2023-06-13 14:39:55', '172.19.0.6', 'Lt3*J', 'showRequest73767a4f-c23b-4dca-b9c3-3cecce3cf92912345678912345677777', 'Approved', ''),
(12345678912345678212, 2, 'OTTENSHEIMERSTRASSE 68', 0.05, '2023-06-08', 'business', '2023-06-13 12:23:13', '172.19.0.6', 'Yh(WM', 'showRequest0efdbbca-023a-407c-a3c9-294989eac36a12345678912345678212', 'In Progress', ''),
(12345678912345678211, 2, 'OTTENSHEIMERSTRASSE 68', 0.05, '2023-06-08', 'business', '2023-06-13 12:14:02', '172.19.0.6', 'G2iBC', 'showRequest0ddcd7e8-d565-4323-b22a-e3de5e6e225e12345678912345678211', 'In Progress', ''),
(12345678912345678210, 2, 'OTTENSHEIMERSTRASSE 68', 0.05, '2023-06-08', 'business', '2023-06-13 12:10:50', '172.19.0.6', 'V4@tD', 'showRequest176b7028-d6bf-4f57-ae3e-b2ef3e5d35a312345678912345678210', 'In Progress', '');

CREATE TABLE admin (
	id int(11) NOT NULL AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	password VARCHAR(50) NOT NULL,
	PRIMARY KEY (id),
	UNIQUE KEY name (name)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 CHARSET=utf8;;

INSERT INTO `admin` VALUES (1, 'admin', '68be59da0cf353ae74ee8db8b005454b515e1a22');

CREATE TABLE log (
	id int(10) NOT NULL AUTO_INCREMENT,
    IPAddress VARCHAR(32),
	action VARCHAR(200),
	userId int(11),
	timestamp DATETIME,
	PRIMARY KEY (id),
	KEY userId (userId)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 CHARSET=utf8;;