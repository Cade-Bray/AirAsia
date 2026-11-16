<?php
// ========================================================
// Air Asia Mileage Rewards Program - Verification Tests
// File: tests/validation-tests.php
// Verifies PDO connection and prepared statements
// ========================================================

require_once __DIR__ . '/../includes/db.php';

echo "=== Running AirAsia Rewards Database & Prepared Statement Tests ===" . PHP_EOL . PHP_EOL;

$passCount = 0;
$failCount = 0;

function assertTest(string $description, bool $condition): void {
    global $passCount, $failCount;
    if ($condition) {
        echo "[PASS] " . $description . PHP_EOL;
        $passCount++;
    } else {
        echo "[FAIL] " . $description . PHP_EOL;
        $failCount++;
    }
}

// 1. Database connection check
assertTest("Database connection instance of PDO", $pdo instanceof PDO);
assertTest("Database error mode is ERRMODE_EXCEPTION", $pdo->getAttribute(PDO::ATTR_ERRMODE) === PDO::ERRMODE_EXCEPTION);
assertTest("Default fetch mode is FETCH_ASSOC", $pdo->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE) === PDO::FETCH_ASSOC);

// 2. Prepared Statement: Select by URL parameter (cardId)
$stmt = $pdo->prepare('SELECT * FROM GiftCard WHERE cardId = :cardId');
$stmt->execute([':cardId' => 1]);
$card = $stmt->fetch();
assertTest("Prepared SELECT by cardId returns valid record", $card !== false && (int)$card['cardId'] === 1);

// 3. Prepared Statement: Filter by URL parameter (cardType)
$stmt = $pdo->prepare('SELECT * FROM GiftCard WHERE cardType = :cardType ORDER BY points ASC');
$stmt->execute([':cardType' => 'Travel']);
$travelCards = $stmt->fetchAll();
assertTest("Prepared SELECT by cardType returns matching list", count($travelCards) > 0 && $travelCards[0]['cardType'] === 'Travel');

// 4. Prepared Statement: Form submission INSERT (card-add.php)
$testCardName = 'Automated Test Gift Card ' . time();
$insertStmt = $pdo->prepare('INSERT INTO GiftCard (cardName, cardType, cardValue, points) VALUES (:cardName, :cardType, :cardValue, :points)');
$insertStmt->execute([
    ':cardName'  => $testCardName,
    ':cardType'  => 'Retail',
    ':cardValue' => 75.00,
    ':points'    => 7500
]);
$newCardId = (int)$pdo->lastInsertId();
assertTest("Prepared INSERT creates new record with lastInsertId", $newCardId > 0);

// Verify inserted record
$checkStmt = $pdo->prepare('SELECT * FROM GiftCard WHERE cardId = :cardId');
$checkStmt->execute([':cardId' => $newCardId]);
$insertedCard = $checkStmt->fetch();
assertTest("Inserted record matches submitted form values", $insertedCard && $insertedCard['cardName'] === $testCardName && (float)$insertedCard['cardValue'] === 75.00);

// 5. Prepared Statement: Form submission UPDATE (card-update.php)
$updatedCardName = $testCardName . ' - Updated';
$updateStmt = $pdo->prepare('UPDATE GiftCard SET cardName = :cardName, cardType = :cardType, cardValue = :cardValue, points = :points WHERE cardId = :cardId');
$updateStmt->execute([
    ':cardName'  => $updatedCardName,
    ':cardType'  => 'Electronics',
    ':cardValue' => 100.00,
    ':points'    => 10000,
    ':cardId'    => $newCardId
]);
assertTest("Prepared UPDATE executes successfully", $updateStmt->rowCount() === 1);

$checkStmt->execute([':cardId' => $newCardId]);
$updatedCard = $checkStmt->fetch();
assertTest("Updated record has modified values", $updatedCard && $updatedCard['cardName'] === $updatedCardName && (int)$updatedCard['points'] === 10000);

// 6. Prepared Statement: DELETE via URL parameter (card-delete.php)
$deleteStmt = $pdo->prepare('DELETE FROM GiftCard WHERE cardId = :cardId');
$deleteStmt->execute([':cardId' => $newCardId]);
assertTest("Prepared DELETE removes the record", $deleteStmt->rowCount() === 1);

$checkStmt->execute([':cardId' => $newCardId]);
$deletedCard = $checkStmt->fetch();
assertTest("Record no longer exists after deletion", $deletedCard === false);

// 7. SQL Injection Prevention Check
$maliciousInput = "1' OR '1'='1";
$sqlInjStmt = $pdo->prepare('SELECT * FROM GiftCard WHERE cardId = :cardId');
$sqlInjStmt->execute([':cardId' => $maliciousInput]);
$injResult = $sqlInjStmt->fetchAll();
assertTest("Prepared statement prevents SQL injection payload from dumping records", count($injResult) <= 1);

// 8. Asset Matching Verification
assertTest("Hilton card maps to dedicated card-hilton.jpg", getCardImage('Hilton Hotel Voucher') === 'images/card-hilton.jpg' && file_exists(__DIR__ . '/../images/card-hilton.jpg'));
assertTest("Marriott card maps to dedicated card-marriott.jpg", getCardImage('Marriott Hotel Voucher') === 'images/card-marriott.jpg' && file_exists(__DIR__ . '/../images/card-marriott.jpg'));
assertTest("Agoda card maps to dedicated card-agoda.jpg", getCardImage('Agoda Travel Credit') === 'images/card-agoda.jpg' && file_exists(__DIR__ . '/../images/card-agoda.jpg'));
assertTest("Baggage card maps to dedicated card-airasia-baggage.jpg", getCardImage('AirAsia Baggage & Seat Voucher') === 'images/card-airasia-baggage.jpg' && file_exists(__DIR__ . '/../images/card-airasia-baggage.jpg'));
assertTest("Meal card maps to dedicated card-airasia-meal.jpg", getCardImage('AirAsia In-Flight Meal Voucher') === 'images/card-airasia-meal.jpg' && file_exists(__DIR__ . '/../images/card-airasia-meal.jpg'));

// 9. Vendor Grouping and Sub-Sorting by Value Ascending Verification
$sortedCardsStmt = $pdo->query('SELECT * FROM GiftCard ORDER BY card_vendor(cardName) ASC, cardValue ASC, points ASC');
$sortedCards = $sortedCardsStmt->fetchAll();
sortCardsByVendorAndValue($sortedCards);

$seenVendors = [];
$currentVendor = null;
$vendorGroupingValid = true;
$vendorValueAscendingValid = true;
$prevValue = 0.0;

foreach ($sortedCards as $card) {
    $vendor = getCardVendor($card['cardName']);
    $val = (float)$card['cardValue'];

    if ($vendor !== $currentVendor) {
        if (isset($seenVendors[$vendor])) {
            // Vendor appeared again non-contiguously!
            $vendorGroupingValid = false;
        }
        $seenVendors[$vendor] = true;
        $currentVendor = $vendor;
        $prevValue = $val;
    } else {
        if ($val < $prevValue) {
            // Within same vendor, value went down!
            $vendorValueAscendingValid = false;
        }
        $prevValue = $val;
    }
}

assertTest("Cards are grouped so like vendors are contiguous", $vendorGroupingValid);
assertTest("Cards within like vendors are sub-sorted by value (lowest -> highest)", $vendorValueAscendingValid);

echo PHP_EOL . "=== Test Results: $passCount passed, $failCount failed ===" . PHP_EOL;

if ($failCount > 0) {
    exit(1);
}
exit(0);
