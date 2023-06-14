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

CREATE TABLE admin (
	id int(11) NOT NULL AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	password VARCHAR(50) NOT NULL,
	PRIMARY KEY (id),
	UNIQUE KEY name (name)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 CHARSET=utf8;;

INSERT INTO `admin` VALUES (1, 'admin', '68be59da0cf353ae74ee8db8b005454b515e1a22');