-- ========================================================
-- Air Asia Mileage Rewards Program Seed Data
-- File: database/seed.sql
-- Inserts required seed data (at least 5 accounts and 20 gift cards)
-- ========================================================

PRAGMA foreign_keys = ON;

-- --------------------------------------------------------
-- Users (Supporting Accounts)
-- --------------------------------------------------------
INSERT INTO User (userId, userName, password, firstName, lastName, role) VALUES
(1, 'admin', 'admin123', 'Sarah', 'Connor', 'Admin'),
(2, 'jdoe', 'password123', 'John', 'Doe', 'Customer'),
(3, 'asmith', 'password123', 'Alice', 'Smith', 'Customer'),
(4, 'bwayne', 'password123', 'Bruce', 'Wayne', 'Customer'),
(5, 'ckent', 'password123', 'Clark', 'Kent', 'Customer');

-- --------------------------------------------------------
-- Accounts (At least 5 records)
-- --------------------------------------------------------
INSERT INTO Account (accountId, userId, accountType, points) VALUES
(1, 1, 'AirAsia Platinum Rewards', 150000),
(2, 2, 'AirAsia Gold Rewards', 25000),
(3, 3, 'AirAsia Silver Rewards', 12000),
(4, 4, 'AirAsia Member Rewards', 8500),
(5, 5, 'AirAsia Member Rewards', 3500);

-- --------------------------------------------------------
-- Gift Cards (At least 20 records)
-- --------------------------------------------------------
INSERT INTO GiftCard (cardId, cardName, cardType, cardValue, points) VALUES
(1, 'AirAsia Flight Voucher $50', 'Travel', 50.00, 5000),
(2, 'AirAsia Flight Voucher $100', 'Travel', 100.00, 10000),
(3, 'AirAsia Baggage & Seat Voucher', 'Travel', 25.00, 2500),
(4, 'AirAsia In-Flight Meal Voucher', 'Travel', 15.00, 1500),
(5, 'Amazon Gift Card $25', 'Shopping', 25.00, 2500),
(6, 'Amazon Gift Card $50', 'Shopping', 50.00, 5000),
(7, 'Apple Gift Card $25', 'Electronics', 25.00, 2500),
(8, 'Apple Gift Card $50', 'Electronics', 50.00, 5000),
(9, 'Grab Transport Voucher', 'Transportation', 10.00, 1000),
(10, 'GrabFood Dining Voucher', 'Food & Dining', 20.00, 2000),
(11, 'Starbucks Coffee Card $10', 'Food & Beverage', 10.00, 1000),
(12, 'Starbucks Coffee Card $25', 'Food & Beverage', 25.00, 2500),
(13, 'Marriott Hotel Voucher', 'Travel & Lodging', 100.00, 10000),
(14, 'Hilton Hotel Voucher', 'Travel & Lodging', 100.00, 10000),
(15, 'Agoda Travel Credit', 'Travel', 50.00, 5000),
(16, 'Spotify Premium 3-Month', 'Entertainment', 30.00, 3000),
(17, 'Netflix Gift Card', 'Entertainment', 30.00, 3000),
(18, 'Google Play Gift Card', 'Digital Goods', 25.00, 2500),
(19, 'Target Gift Card', 'Retail', 25.00, 2500),
(20, 'Best Buy Gift Card', 'Electronics', 50.00, 5000),
(21, 'Sephora Beauty Voucher', 'Retail', 25.00, 2500);

-- --------------------------------------------------------
-- Sample Redemptions
-- --------------------------------------------------------
INSERT INTO Redemption (redeemId, date, accountId, cardId, pointsRedeemed) VALUES
(1, '2026-10-15', 2, 11, 1000),
(2, '2026-11-01', 3, 5, 2500);
