-- ========================================================
-- Air Asia Mileage Rewards Program Database Schema
-- File: database/schema.sql
-- ========================================================

PRAGMA foreign_keys = ON;

DROP TABLE IF EXISTS Redemption;
DROP TABLE IF EXISTS Account;
DROP TABLE IF EXISTS GiftCard;
DROP TABLE IF EXISTS User;

-- --------------------------------------------------------
-- User Table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS User (
    userId INTEGER PRIMARY KEY AUTOINCREMENT,
    userName VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    firstName VARCHAR(100) NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL
);

-- --------------------------------------------------------
-- Account Table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS Account (
    accountId INTEGER PRIMARY KEY AUTOINCREMENT,
    userId INT(11) NOT NULL,
    accountType VARCHAR(100) NOT NULL,
    points INT(50) NOT NULL,
    FOREIGN KEY (userId) REFERENCES User (userId)
);

-- --------------------------------------------------------
-- Gift Card Table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS GiftCard (
    cardId INTEGER PRIMARY KEY AUTOINCREMENT,
    cardName VARCHAR(100) NOT NULL,
    cardType VARCHAR(100) NOT NULL,
    cardValue FLOAT(10) NOT NULL,
    points INT(50) NOT NULL
);

-- --------------------------------------------------------
-- Redemption Table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS Redemption (
    redeemId INTEGER PRIMARY KEY AUTOINCREMENT,
    date VARCHAR(100) NOT NULL,
    accountId INT(11) NOT NULL,
    cardId INT(11) NOT NULL,
    pointsRedeemed INT(50) NOT NULL,
    FOREIGN KEY (accountId) REFERENCES Account (accountId),
    FOREIGN KEY (cardId) REFERENCES GiftCard (cardId)
);
