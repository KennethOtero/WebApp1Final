-- --------------------------------------------------------------------------------
-- Kenneth Otero
-- Web App 1
-- Abstract: Database
-- Date Started: April 5, 2022
-- --------------------------------------------------------------------------------
-- USE dbsql; -- Use our local database

-- --------------------------------------------------------------------------------
-- Drop Tables
-- --------------------------------------------------------------------------------
DROP TABLE IF EXISTS TEventGolferSponsors;
DROP TABLE IF EXISTS TEventGolfers;
DROP TABLE IF EXISTS TSponsors;
DROP TABLE IF EXISTS TGolfers;
DROP TABLE IF EXISTS TEvents;
DROP TABLE IF EXISTS TGenders;
DROP TABLE IF EXISTS TPaymentStatuses;
DROP TABLE IF EXISTS TPaymentTypes;
DROP TABLE IF EXISTS TShirtSizes;
DROP TABLE IF EXISTS TStates;
DROP TABLE IF EXISTS TAdmins;

-- --------------------------------------------------------------------------------
-- Drop Views
-- --------------------------------------------------------------------------------
DROP VIEW IF EXISTS vMostRecentDonation;

-- --------------------------------------------------------------------------------
-- Drop Stored Procedures
-- --------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS uspTotalDonations;
DROP PROCEDURE IF EXISTS uspTopGolferDonations;
DROP PROCEDURE IF EXISTS uspViewGolfersInEvent;
DROP PROCEDURE IF EXISTS uspValidateLogin;
DROP PROCEDURE IF EXISTS uspTotalPledged;
DROP PROCEDURE IF EXISTS uspTotalCollected;
DROP PROCEDURE IF EXISTS uspTotalPledges;
DROP PROCEDURE IF EXISTS uspTotalCountOfDonations;
DROP PROCEDURE IF EXISTS uspAverageDonation;
DROP PROCEDURE IF EXISTS uspAddGolfer;
DROP PROCEDURE IF EXISTS uspUpdateGolfer;
DROP PROCEDURE IF EXISTS uspTop3Golfers;
DROP PROCEDURE IF EXISTS uspAddEvent;
DROP PROCEDURE IF EXISTS uspAddSponsor;
DROP PROCEDURE IF EXISTS uspAddEventGolferSponsor;
DROP PROCEDURE IF EXISTS uspShowDonors;
DROP PROCEDURE IF EXISTS uspGolferTotalPledged;
DROP PROCEDURE IF EXISTS uspGolferTotalCollected;
DROP PROCEDURE IF EXISTS uspUpdatePaymentStatus;

-- --------------------------------------------------------------------------------
-- Create Tables
-- --------------------------------------------------------------------------------
CREATE TABLE TStates (
	intStateID				INTEGER AUTO_INCREMENT 	NOT NULL,
    strState				VARCHAR(250)			NOT NULL,
    CONSTRAINT TStates_PK PRIMARY KEY (intStateID)
);

CREATE TABLE TGenders (
	intGenderID				INTEGER AUTO_INCREMENT 	NOT NULL,
    strGender				VARCHAR(250)			NOT NULL,
    CONSTRAINT TGenders_PK PRIMARY KEY (intGenderID)
);

CREATE TABLE TShirtSizes (
	intShirtSizeID			INTEGER AUTO_INCREMENT	NOT NULL,
    strShirtSize			VARCHAR(250)			NOT NULL,
    CONSTRAINT TShirtSizes_PK PRIMARY KEY (intShirtSizeID)
);

CREATE TABLE TEvents (
	intEventID				INTEGER AUTO_INCREMENT 	NOT NULL,
    intEventYear			INT						NOT NULL,
    CONSTRAINT TEvents_UQ UNIQUE (intEventYear),
    CONSTRAINT TEvents_PK PRIMARY KEY (intEventID)
);

CREATE TABLE TPaymentTypes (
	intPaymentTypeID		INTEGER AUTO_INCREMENT 	NOT NULL,
    strPaymentType			VARCHAR(250)			NOT NULL,
    CONSTRAINT TPaymentTypes_PK PRIMARY KEY (intPaymentTypeID)
);

CREATE TABLE TPaymentStatuses (
	intPaymentStatusID		INTEGER AUTO_INCREMENT	NOT NULL,
    strPaymentStatus		VARCHAR(250)			NOT NULL,
    CONSTRAINT TPaymentStatuses_PK PRIMARY KEY (intPaymentStatusID)
);

CREATE TABLE TSponsors (
	intSponsorID			INTEGER AUTO_INCREMENT	NOT NULL,
    strFirstName			VARCHAR(250)			NOT NULL,
    strLastName				VARCHAR(250)			NOT NULL,
    strAddress				VARCHAR(250)			NOT NULL,
    strCity					VARCHAR(250)			NOT NULL,
    intStateID				INTEGER					NOT NULL,
    strZip					VARCHAR(250)			NOT NULL,
    strPhoneNumber			VARCHAR(250)			NOT NULL,
    strEmail				VARCHAR(250)			NOT NULL,
    CONSTRAINT TSponsors_UQ UNIQUE (strEmail),
    CONSTRAINT TSponsors_PK PRIMARY KEY (intSponsorID)
);

CREATE TABLE TGolfers (
	intGolferID				INTEGER AUTO_INCREMENT	NOT NULL,
    strFirstName			VARCHAR(250)			NOT NULL,
    strLastName				VARCHAR(250)			NOT NULL,
    strAddress				VARCHAR(250)			NOT NULL,
    strCity					VARCHAR(250)			NOT NULL,
    intStateID				INTEGER					NOT NULL,
    strZip					VARCHAR(250)			NOT NULL,
    strPhoneNumber			VARCHAR(250)			NOT NULL,
    strEmail				VARCHAR(250)			NOT NULL,
    intShirtSizeID			INTEGER					NOT NULL,
    intGenderID				INTEGER					NOT NULL,
    CONSTRAINT TGolfers_UQ UNIQUE (strEmail),
    CONSTRAINT TGolfers_PK PRIMARY KEY (intGolferID)
);

CREATE TABLE TEventGolfers (
	intEventGolferID		INTEGER AUTO_INCREMENT	NOT NULL,
    intEventID				INTEGER					NOT NULL,
    intGolferID				INTEGER					NOT NULL,
    CONSTRAINT TEventGolfers_PK PRIMARY KEY (intEventGolferID)
);

CREATE TABLE TEventGolferSponsors (
	intEventGolferSponsorID	INTEGER AUTO_INCREMENT 	NOT NULL,
    intEventGolferID		INTEGER					NOT NULL,
    intSponsorID			INTEGER					NOT NULL,
    dtmDateOfPledge			DATETIME				NOT NULL,
    decPledgePerHole		DECIMAL(13,2)			NOT NULL,
    intPaymentTypeID		INTEGER					NOT NULL,
    intPaymentStatusID		INTEGER					NOT NULL,
    CONSTRAINT TEventGolferSponsors_PK PRIMARY KEY (intEventGolferSponsorID)
);

CREATE TABLE TAdmins (
	intAdminID				INTEGER AUTO_INCREMENT 	NOT NULL,
    intLoginID				INTEGER					NOT NULL,
    strFirstName			VARCHAR(250)			NOT NULL,
    strLastName				VARCHAR(250)			NOT NULL,
    strPassword				VARCHAR(250)			NOT NULL,
    CONSTRAINT TAdmins_UQ UNIQUE (intLoginID),
    CONSTRAINT TAdmins_PK PRIMARY KEY (intAdminID)
);

-- --------------------------------------------------------------------------------
-- Identify and Create Foreign Keys
-- --------------------------------------------------------------------------------
--
-- #	Child								Parent						Column(s)
-- -	-----								------						---------
-- 1	TSponsors							TStates						intStateID
-- 2	TGolfers							TStates						intStateID
-- 3	TGolfers							TShirtSizes					intShirtSizeID
-- 4	TGolfers							TGenders					intGenderID
-- 5	TEventGolfers						TEvents						intEventID
-- 6	TEventGolfers						TGolfers					intGolferID
-- 7	TEventGolferSponsors				TEventGolfers				intEventGolferID
-- 8	TEventGolferSponsors				TSponsors					intSponsorID
-- 9	TEventGolferSponsors				TPaymentTypes				intPaymentTypeID
-- 10	TEventGolferSponsors				TPaymentStatuses			intPaymentStatusID

-- 1
ALTER TABLE TSponsors ADD CONSTRAINT TSponsors_TStates_FK
FOREIGN KEY (intStateID) REFERENCES TStates (intStateID);

-- 2
ALTER TABLE TGolfers ADD CONSTRAINT TGolfers_TStates_FK
FOREIGN KEY (intStateID) REFERENCES TStates (intStateID);

-- 3
ALTER TABLE TGolfers ADD CONSTRAINT TGolfers_TShirtSizes_FK
FOREIGN KEY (intShirtSizeID) REFERENCES TShirtSizes (intShirtSizeID);

-- 4
ALTER TABLE TGolfers ADD CONSTRAINT TGolfers_TGenders_FK
FOREIGN KEY (intGenderID) REFERENCES TGenders (intGenderID);

-- 5
ALTER TABLE TEventGolfers ADD CONSTRAINT TEventGolfers_TEvents_FK
FOREIGN KEY (intEventID) REFERENCES TEvents (intEventID);

-- 6
ALTER TABLE TEventGolfers ADD CONSTRAINT TEventGolfers_TGolfers_FK
FOREIGN KEY (intGolferID) REFERENCES TGolfers (intGolferID);

-- 7
ALTER TABLE TEventGolferSponsors ADD CONSTRAINT TEventGolferSponsors_TEventGolfers_FK
FOREIGN KEY (intEventGolferID) REFERENCES TEventGolfers (intEventGolferID);

-- 8
ALTER TABLE TEventGolferSponsors ADD CONSTRAINT TEventGolferSponsors_TSponsors_FK
FOREIGN KEY (intSponsorID) REFERENCES TSponsors (intSponsorID);

-- 9
ALTER TABLE TEventGolferSponsors ADD CONSTRAINT TEventGolferSponsors_TPaymentTypes_FK
FOREIGN KEY (intPaymentTypeID) REFERENCES TPaymentTypes (intPaymentTypeID);

-- 10
ALTER TABLE TEventGolferSponsors ADD CONSTRAINT TEventGolferSponsors_TPaymentStatuses_FK
FOREIGN KEY (intPaymentStatusID) REFERENCES TPaymentStatuses (intPaymentStatusID);

-- --------------------------------------------------------------------------------
-- Insert Data
-- --------------------------------------------------------------------------------
INSERT INTO TStates (strState)
VALUES	('Alabama')
	   ,('Alaska')
	   ,('Arizona')
	   ,('Arkansas')
	   ,('California')
	   ,('Colorado')
	   ,('Connecticut')
	   ,('Delaware')
	   ,('Florida')
	   ,('Georgia')
	   ,('Hawaii')
	   ,('Idaho')
	   ,('Illinois')
	   ,('Indiana')
	   ,('Iowa')
	   ,('Kansas')
	   ,('Kentucky')
	   ,('Louisiana')
	   ,('Maine')
	   ,('Maryland')
	   ,('Massachusetts')
	   ,('Michigan')
	   ,('Minnesota')
	   ,('Mississippi')
	   ,('Missouri')
	   ,('Montana')
	   ,('Nebraska')
	   ,('Nevada')
	   ,('New Hampshire')
	   ,('New Jersey')
	   ,('New Mexico')
	   ,('New York')
	   ,('North Carolina')
	   ,('North Dakota')
	   ,('Ohio')
	   ,('Oklahoma')
	   ,('Oregon')
	   ,('Pennsylvania')
	   ,('Rhode Island')
	   ,('South Carolina')
	   ,('South Dakota')
	   ,('Tennessee')
	   ,('Texas')
	   ,('Utah')
	   ,('Vermont')
	   ,('Virginia')
	   ,('Washington')
	   ,('West Virginia')
	   ,('Wisconsin')
	   ,('Wyoming');
        
INSERT INTO TShirtSizes (strShirtSize)
VALUES	('Small'),
		('Medium'),
        ('Large');
        
INSERT INTO TGenders (strGender)
VALUES	('Male'),
		('Female');
        
INSERT INTO TPaymentTypes (strPaymentType)
VALUES	('Cash'),
		('Credit'),
        ('Check');
        
INSERT INTO TPaymentStatuses (strPaymentStatus)
VALUES	('Paid'),
		('Unpaid');
                
-- --------------------------------------------------------------------------------
-- Test Data
-- --------------------------------------------------------------------------------
INSERT INTO TEvents (intEventYear)
VALUES	(2019),
		(2021),
        (2022);
        
INSERT INTO TGolfers (strFirstName, strLastName, strAddress, strCity, intStateID, strZip, strPhoneNumber, strEmail, intShirtSizeID, intGenderID)
VALUES	('Ken', 'Otero', '123 Main', 'Cincinnati', 1, '45211', '111-111-1111', 'ken@gmail.com', 3, 1),
		('Julie', 'Otero', '123 Main', 'Cincinnati', 1, '45211', '222-222-2222', 'julie@gmail.com', 1, 2),
        ('Ben', 'Parish', '123 Main', 'Cincinnati', 2, '45211', '333-333-3333', 'ben@gmail.com', 3, 1);

INSERT INTO TEventGolfers (intEventID, intGolferID)
VALUES	(3, 1),
		(3, 2),
        (3, 3);
        
INSERT INTO TSponsors (strFirstName, strLastName, strAddress, strCity, intStateID, strZip, strPhoneNumber, strEmail)
VALUES	('Cassie', 'Sullivan', '123 Main', 'Cincinnati', 1, '45211', '444-444-4444', 'cassie@gmail.com'),
		('Evan', 'Walker', '123 Main', 'Cincinnati', 2, '45211', '555-555-5555', 'evan@gmail.com'),
        ('Grace', 'Sullivan', '123 Main', 'Cincinnati', 3, '45211', '666-666-6666', 'grace@gmail.com');
        
INSERT INTO TAdmins (strFirstName, strLastName, intLoginID, strPassword)
VALUES	('Max', 'Vosch', 111, 'password'),
		('Sam', 'Sullivan', 222, 'password'),
        ('James', 'Sullivan', 333, 'password');
        
INSERT INTO TEventGolferSponsors (intEventGolferID, intSponsorID, dtmDateOfPledge, decPledgePerHole, intPaymentTypeID, intPaymentStatusID)
VALUES	(1, 1, NOW(), 50.00, 1, 1),
		(2, 2, NOW(), 75.00, 2, 1),
        (3, 3, NOW(), 100.00, 3, 2),
        (3, 1, NOW(), 100.00, 3, 2);
        
-- --------------------------------------------------------------------------------
-- Views
-- -------------------------------------------------------------------------------- 
CREATE VIEW vMostRecentDonation
AS
	SELECT 
		CONCAT(TS.strFirstName,' ', TS.strLastName) as SponsorName,
        TEGS.decPledgePerHole as Donation
    FROM
		TGolfers as TG JOIN TEventGolfers as TEG
			ON TG.intGolferID = TEG.intGolferID
		JOIN TEvents as TE
			ON TE.intEventID = TEG.intEventID
		JOIN TEventGolferSponsors as TEGS
			ON TEGS.intEventGolferID = TEG.intEventGolferID
		JOIN TSponsors as TS
			ON TS.intSponsorID = TEGS.intSponsorID
		JOIN TPaymentStatuses as TPS
			ON TPS.intPaymentStatusID = TEGS.intPaymentStatusID
	WHERE
		TPS.strPaymentStatus = 'Paid'
	ORDER BY 
		intEventGolferSponsorID DESC LIMIT 1;

-- --------------------------------------------------------------------------------
-- Stored Procedures
-- --------------------------------------------------------------------------------
-- Get total donations of all golfers based on event
DELIMITER //
CREATE PROCEDURE uspTotalDonations (
	IN p_intEventYear INT
)
BEGIN
	SELECT 
		SUM(TEGS.decPledgePerHole) as TotalDono
    FROM
		TGolfers as TG JOIN TEventGolfers as TEG
			ON TG.intGolferID = TEG.intGolferID
		JOIN TEvents as TE
			ON TE.intEventID = TEG.intEventID
		JOIN TEventGolferSponsors as TEGS
			ON TEGS.intEventGolferID = TEG.intEventGolferID
		JOIN TSponsors as TS
			ON TS.intSponsorID = TEGS.intSponsorID
    WHERE
		TE.intEventYear = p_intEventYear;
END //
DELIMITER ;

-- Find the golfer with the most donations for the current event
DELIMITER //
CREATE PROCEDURE uspTopGolferDonations (
	IN p_intEventYear INT
)
BEGIN
	SELECT
		CONCAT(TG.strFirstName,' ',TG.strLastName) as GolferName
	   ,SUM(TEGS.decPledgePerHole) as TotalPledges
	   ,TE.intEventYear
	FROM
		TGolfers as TG JOIN TEventGolfers as TEG
			ON TG.intGolferID = TEG.intGolferID
		JOIN TEvents as TE 
			ON TE.intEventID = TEG.intEventID
		JOIN TEventGolferSponsors as TEGS
			ON TEGS.intEventGolferID = TEG.intEventGolferID
	WHERE TE.intEventYear = p_intEventYear 
	GROUP BY 
		TG.strFirstName
	   ,TG.strLastName
	   ,TE.intEventYear
	ORDER BY SUM(TEGS.decPledgePerHole) DESC LIMIT 1;
END //
DELIMITER ;

-- Find all the golfers for an event
DELIMITER //
CREATE PROCEDURE uspViewGolfersInEvent (
	IN p_intEventYear INT
)
BEGIN
	SELECT
       COUNT(TG.intGolferID) as TotalGolfers
    FROM
		TGolfers as TG JOIN TEventGolfers as TEG
			ON TG.intGolferID = TEG.intGolferID
		JOIN TEvents as TE
			ON TE.intEventID = TEG.intEventID
	WHERE
		TE.intEventYear = p_intEventYear;
END //
DELIMITER ;

-- Check login
DELIMITER //
CREATE PROCEDURE uspValidateLogin (
	IN p_intLoginID VARCHAR(250),
	IN p_strPassword VARCHAR(250)
)
BEGIN
	SELECT
		TA.intAdminID,
        TA.intLoginID,
        TA.strFirstName,
        TA.strLastName,
        TA.strPassword
    FROM
		TAdmins as TA
    WHERE
		TA.strPassword = p_strPassword AND TA.intLoginID = p_intLoginID;
END //
DELIMITER ;

-- Display total pledged
DELIMITER //
CREATE PROCEDURE uspTotalPledged (
	IN p_intEventYear INT
)
BEGIN
	SELECT
		SUM(TEGS.decPledgePerHole) as TotalPledge
    FROM
		TGolfers as TG JOIN TEventGolfers as TEG
			ON TG.intGolferID = TEG.intGolferID
		JOIN TEvents as TE
			ON TE.intEventID = TEG.intEventID
		JOIN TEventGolferSponsors as TEGS
			ON TEGS.intEventGolferID = TEG.intEventGolferID
		JOIN TSponsors as TS
			ON TS.intSponsorID = TEGS.intSponsorID
		JOIN TPaymentStatuses as TPS
			ON TPS.intPaymentStatusID = TEGS.intPaymentStatusID
    WHERE
		TE.intEventYear = p_intEventYear AND TPS.strPaymentStatus = 'Unpaid';
END //
DELIMITER ;

-- Display total collected
DELIMITER //
CREATE PROCEDURE uspTotalCollected (
	IN p_intEventYear INT
)
BEGIN
	SELECT
		SUM(TEGS.decPledgePerHole) as TotalCollected
    FROM
		TGolfers as TG JOIN TEventGolfers as TEG
			ON TG.intGolferID = TEG.intGolferID
		JOIN TEvents as TE
			ON TE.intEventID = TEG.intEventID
		JOIN TEventGolferSponsors as TEGS
			ON TEGS.intEventGolferID = TEG.intEventGolferID
		JOIN TSponsors as TS
			ON TS.intSponsorID = TEGS.intSponsorID
		JOIN TPaymentStatuses as TPS
			ON TPS.intPaymentStatusID = TEGS.intPaymentStatusID
    WHERE
		TE.intEventYear = p_intEventYear AND TPS.strPaymentStatus = 'Paid';
END //
DELIMITER ;

-- Display the total dollar amount of pledges
DELIMITER //
CREATE PROCEDURE uspTotalPledges (
	IN p_intEventYear INT
)
BEGIN
	SELECT
		SUM(TEGS.decPledgePerHole) as TotalPledge
    FROM
		TGolfers as TG JOIN TEventGolfers as TEG
			ON TG.intGolferID = TEG.intGolferID
		JOIN TEvents as TE
			ON TE.intEventID = TEG.intEventID
		JOIN TEventGolferSponsors as TEGS
			ON TEGS.intEventGolferID = TEG.intEventGolferID
		JOIN TSponsors as TS
			ON TS.intSponsorID = TEGS.intSponsorID
		JOIN TPaymentStatuses as TPS
			ON TPS.intPaymentStatusID = TEGS.intPaymentStatusID
    WHERE
		TE.intEventYear = p_intEventYear AND TPS.strPaymentStatus = 'Unpaid';
END //
DELIMITER ;

-- Display the total # of donations
DELIMITER //
CREATE PROCEDURE uspTotalCountOfDonations (
	IN p_intEventYear INT
)
BEGIN
	SELECT
		COUNT(TEGS.decPledgePerHole) as TotalDono
    FROM
		TGolfers as TG JOIN TEventGolfers as TEG
			ON TG.intGolferID = TEG.intGolferID
		JOIN TEvents as TE
			ON TE.intEventID = TEG.intEventID
		JOIN TEventGolferSponsors as TEGS
			ON TEGS.intEventGolferID = TEG.intEventGolferID
		JOIN TSponsors as TS
			ON TS.intSponsorID = TEGS.intSponsorID
		JOIN TPaymentStatuses as TPS
			ON TPS.intPaymentStatusID = TEGS.intPaymentStatusID
    WHERE
		TE.intEventYear = p_intEventYear AND TPS.strPaymentStatus = 'Paid';
END //
DELIMITER ;

-- Display the average # of donations
DELIMITER //
CREATE PROCEDURE uspAverageDonation (
	IN p_intEventYear INT
)
BEGIN
	SELECT
		AVG(TEGS.decPledgePerHole) as AverageDono
    FROM
		TGolfers as TG JOIN TEventGolfers as TEG
			ON TG.intGolferID = TEG.intGolferID
		JOIN TEvents as TE
			ON TE.intEventID = TEG.intEventID
		JOIN TEventGolferSponsors as TEGS
			ON TEGS.intEventGolferID = TEG.intEventGolferID
		JOIN TSponsors as TS
			ON TS.intSponsorID = TEGS.intSponsorID
		JOIN TPaymentStatuses as TPS
			ON TPS.intPaymentStatusID = TEGS.intPaymentStatusID
    WHERE
		TE.intEventYear = p_intEventYear;
END //
DELIMITER ;

-- Insert a golfer & insert them into an event
DELIMITER // 
CREATE PROCEDURE uspAddGolfer (
    IN p_strFirstName VARCHAR(250),
    IN p_strLastName VARCHAR(250),
    IN p_strAddress VARCHAR(250),
    IN p_strCity VARCHAR(250),
    IN p_intStateID INT,
    IN p_strZip VARCHAR(250),
    IN p_strPhone VARCHAR(250),
    IN p_strEmail VARCHAR(250),
    IN p_intShirtSizeID INT,
    IN p_intGenderID INT
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;  -- Rollback any error in the transaction
        RESIGNAL;  -- Return error information
    END;
START TRANSACTION;
	-- Insert into TGolfers
    INSERT INTO TGolfers (strFirstName, strLastName, strAddress, strCity, intStateID, strZip, strPhoneNumber, strEmail, intShirtSizeID, intGenderID)
    VALUES (p_strFirstName, p_strLastName, p_strAddress, p_strCity, p_intStateID, p_strZip, p_strPhone, p_strEmail, p_intShirtSizeID, p_intGenderID);
    
    -- Get most recent intEventID
    SET @intEventID = 0;
    SELECT MAX(intEventID) INTO @intEventID FROM TEvents;
    
    -- Get most recent intGolferID
    SET @intGolferID = 0;
    SELECT MAX(intGolferID) INTO @intGolferID FROM TGolfers;
    
    -- Insert into TEventGolfers
    INSERT INTO TEventGolfers (intEventID, intGolferID)
    VALUES (@intEventID, @intGolferID);
COMMIT;
END //
DELIMITER ;

-- Update a golfer
DELIMITER //
CREATE PROCEDURE uspUpdateGolfer (
	IN p_intGolferID INT,
	IN p_strFirstName VARCHAR(250),
    IN p_strLastName VARCHAR(250),
    IN p_strAddress VARCHAR(250),
    IN p_strCity VARCHAR(250),
    IN p_intStateID INT,
    IN p_strZip VARCHAR(250),
    IN p_strPhone VARCHAR(250),
    IN p_strEmail VARCHAR(250),
    IN p_intShirtSizeID INT,
    IN p_intGenderID INT
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;  -- Rollback any error in the transaction
        RESIGNAL;  -- Return error information
    END;
START TRANSACTION;
	-- Update Golfer
    UPDATE TGolfers
    SET
		strFirstName = p_strFirstName,
		strLastName = p_strLastName,
		strAddress = p_strAddress,
		strCity = p_strCity,
		intStateID = p_intStateID,
		strZip = p_strZip,
		strPhoneNumber = p_strPhone,
		strEmail = p_strEmail,
		intShirtSizeID = p_intShirtSizeID,
		intGenderID = p_intGenderID
    WHERE
		intGolferID = p_intGolferID;
COMMIT;
END //
DELIMITER ;

-- Get top 3 golfers based on donations
DELIMITER //
CREATE PROCEDURE uspTop3Golfers (
	IN p_intEventYear INT
)
BEGIN
	SELECT
		CONCAT(TG.strFirstName,' ',TG.strLastName) as GolferName
	   ,SUM(TEGS.decPledgePerHole) as TotalPledges
	   ,TE.intEventYear
       ,TG.intGolferID
	FROM
		TGolfers as TG JOIN TEventGolfers as TEG
			ON TG.intGolferID = TEG.intGolferID
		JOIN TEvents as TE 
			ON TE.intEventID = TEG.intEventID
		JOIN TEventGolferSponsors as TEGS
			ON TEGS.intEventGolferID = TEG.intEventGolferID
	WHERE TE.intEventYear = p_intEventYear 
	GROUP BY 
		TG.strFirstName
	   ,TG.strLastName
	   ,TE.intEventYear
	ORDER BY SUM(TEGS.decPledgePerHole) DESC;
END //
DELIMITER ;

-- Add an event
DELIMITER // 
CREATE PROCEDURE uspAddEvent (
	IN p_intEventYear INT
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;  -- Rollback any error in the transaction
        RESIGNAL;  -- Return error information
    END;
START TRANSACTION;
	INSERT INTO TEvents (intEventYear)
    VALUES	(p_intEventYear);
COMMIT;
END //
DELIMITER ;

-- Insert a sponsor
DELIMITER //
CREATE PROCEDURE uspAddSponsor (
	IN p_strFirstName VARCHAR(250),
    IN p_strLastName VARCHAR(250),
    IN p_strAddress VARCHAR(250),
    IN p_strCity VARCHAR(250),
    IN p_intStateID INT,
    IN p_strZip VARCHAR(250),
    IN p_strPhone VARCHAR(250),
    IN p_strEmail VARCHAR(250),
    OUT p_intSponsorID INT
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;  -- Rollback any error in the transaction
        RESIGNAL;  -- Return error information
    END;
START TRANSACTION;
	INSERT INTO TSponsors (strFirstName, strLastName, strAddress, strCity, intStateID, strZip, strPhoneNumber, strEmail)
    VALUES (p_strFirstName, p_strLastName, p_strAddress, p_strCity, p_intStateID, p_strZip, p_strPhone, p_strEmail);

	-- Get the latest PK
    SELECT intSponsorID INTO p_intSponsorID FROM TSponsors ORDER BY intSponsorID DESC LIMIT 1;
COMMIT;
END //
DELIMITER ;

-- Insert into TEventGolferSponsors
DELIMITER //
CREATE PROCEDURE uspAddEventGolferSponsor (
	IN p_intEventGolferID INT,
    IN p_intSponsorID INT,
    IN p_Donation DECIMAL(13, 2),
    IN p_intPaymentTypeID INT
)
BEGIN 
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;  -- Rollback any error in the transaction
        RESIGNAL;  -- Return error information
    END;
START TRANSACTION;
	-- Set to paid if the sponsor pays with credit (id = 2)
	IF p_intPaymentTypeID = 2 THEN
		SET @PayStatus = 1;
	ELSE
		SET @PayStatus = 2;
    END IF;
    
	INSERT INTO TEventGolferSponsors (intEventGolferID, intSponsorID, dtmDateOfPledge, decPledgePerHole, intPaymentTypeID, intPaymentStatusID)
    VALUES (p_intEventGolferID, p_intSponsorID, NOW(), p_Donation, p_intPaymentTypeID, @PayStatus);
COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE uspShowDonors (
	IN p_intGolferID INT
)
BEGIN
	SELECT
		CONCAT(TS.strFirstName, ' ', TS.strLastName) as SponsorName,
        TS.intSponsorID,
        TEGS.decPledgePerHole,
        TEGS.intPaymentStatusID,
        TPS.strPaymentStatus,
        TEGS.intPaymentTypeID,
        TPT.strPaymentType
    FROM
		TGolfers as TG JOIN TEventGolfers as TEG
			ON TG.intGolferID = TEG.intGolferID
		JOIN TEvents as TE
			ON TE.intEventID = TEG.intEventID
		JOIN TEventGolferSponsors as TEGS
			ON TEGS.intEventGolferID = TEG.intEventGolferID
		JOIN TSponsors as TS
			ON TS.intSponsorID = TEGS.intSponsorID
		JOIN TPaymentStatuses as TPS
			ON TPS.intPaymentStatusID = TEGS.intPaymentStatusID
		JOIN TPaymentTypes as TPT
			ON TPT.intPaymentTypeID = TEGS.intPaymentTypeID
    WHERE
		TG.intGolferID = p_intGolferID;
END //
DELIMITER ;

-- Display the total pledged for a golfer
DELIMITER //
CREATE PROCEDURE uspGolferTotalPledged (
	IN p_intGolferID INT
)
BEGIN
	SELECT 
		TG.intGolferID,
        CONCAT(TG.strFirstName, ' ', TG.strLastName) as GolferName,
        IFNULL(SUM(TEGS.decPledgePerHole), 0) as TotalPledged
    FROM
		TGolfers as TG JOIN TEventGolfers as TEG
			ON TG.intGolferID = TEG.intGolferID
		JOIN TEvents as TE
			ON TE.intEventID = TEG.intEventID
		JOIN TEventGolferSponsors as TEGS
			ON TEGS.intEventGolferID = TEG.intEventGolferID
		JOIN TSponsors as TS
			ON TS.intSponsorID = TEGS.intSponsorID
		JOIN TPaymentStatuses as TPS
			ON TPS.intPaymentStatusID = TEGS.intPaymentStatusID
		JOIN TPaymentTypes as TPT
			ON TPT.intPaymentTypeID = TEGS.intPaymentTypeID
    WHERE
		TG.intGolferID = p_intGolferID AND TPS.strPaymentStatus = 'Unpaid'
	GROUP BY
		TG.intGolferID;
END //
DELIMITER ;

-- Display the total collected for a golfer
DELIMITER //
CREATE PROCEDURE uspGolferTotalCollected (
	IN p_intGolferID INT
)
BEGIN
	SELECT 
		TG.intGolferID,
        CONCAT(TG.strFirstName, ' ', TG.strLastName) as GolferName,
        IFNULL(SUM(TEGS.decPledgePerHole), 0) as TotalCollected
    FROM
		TGolfers as TG JOIN TEventGolfers as TEG
			ON TG.intGolferID = TEG.intGolferID
		JOIN TEvents as TE
			ON TE.intEventID = TEG.intEventID
		JOIN TEventGolferSponsors as TEGS
			ON TEGS.intEventGolferID = TEG.intEventGolferID
		JOIN TSponsors as TS
			ON TS.intSponsorID = TEGS.intSponsorID
		JOIN TPaymentStatuses as TPS
			ON TPS.intPaymentStatusID = TEGS.intPaymentStatusID
		JOIN TPaymentTypes as TPT
			ON TPT.intPaymentTypeID = TEGS.intPaymentTypeID
    WHERE
		TG.intGolferID = p_intGolferID AND TPS.strPaymentStatus = 'Paid'
	GROUP BY
		TG.intGolferID;
END //
DELIMITER ;

-- Update payment status
DELIMITER //
CREATE PROCEDURE uspUpdatePaymentStatus (
	IN p_intSponsorID INT,
    IN p_intPaymentStatusID INT
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;  -- Rollback any error in the transaction
        RESIGNAL;  -- Return error information
    END;
START TRANSACTION;
	UPDATE TEventGolferSponsors 
    SET
		intPaymentStatusID = p_intPaymentStatusID
	WHERE
		intSponsorID = p_intSponsorID;
COMMIT;
END //
DELIMITER ;

-- CALL uspTopGolferDonations(2022);
-- CALL uspViewGolfersInEvent(2022);