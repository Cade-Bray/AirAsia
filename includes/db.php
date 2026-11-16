<?php
// ========================================================
// Air Asia Mileage Rewards Program - Database Connection
// File: includes/db.php
// Centralized PDO connection to SQLite database
// ========================================================

$dbPath = __DIR__ . '/../database/rewards.db';

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON;');
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

if (!function_exists('getDbConnection')) {
    function getDbConnection(): PDO {
        global $pdo;
        return $pdo;
    }
}

if (!function_exists('getCardVendor')) {
    /**
     * Helper to extract vendor name from card name for grouping
     */
    function getCardVendor(?string $cardName): string {
        if (!$cardName) {
            return 'Other';
        }
        $name = trim($cardName);
        $knownVendors = [
            'AirAsia'     => ['airasia'],
            'Amazon'      => ['amazon'],
            'Apple'       => ['apple'],
            'Best Buy'    => ['best buy', 'bestbuy'],
            'Google Play' => ['google play', 'google'],
            'Grab'        => ['grabfood', 'grab'],
            'Hilton'      => ['hilton'],
            'Marriott'    => ['marriott'],
            'Agoda'       => ['agoda'],
            'Netflix'     => ['netflix'],
            'Sephora'     => ['sephora'],
            'Spotify'     => ['spotify'],
            'Starbucks'   => ['starbucks'],
            'Target'      => ['target'],
        ];
        $lower = strtolower($name);
        foreach ($knownVendors as $vendor => $patterns) {
            foreach ($patterns as $pattern) {
                if (str_starts_with($lower, $pattern) || str_contains($lower, $pattern)) {
                    return $vendor;
                }
            }
        }
        $parts = explode(' ', $name);
        return !empty($parts[0]) ? $parts[0] : $name;
    }
}

if (isset($pdo) && method_exists($pdo, 'sqliteCreateFunction')) {
    $pdo->sqliteCreateFunction('card_vendor', 'getCardVendor', 1);
}

if (!function_exists('sortCardsByVendorAndValue')) {
    /**
     * Sorts an array of gift card records to group like vendors together
     * and then sub-sorted by value (cardValue) ascending (lowest -> highest).
     */
    function sortCardsByVendorAndValue(array &$cards): void {
        usort($cards, function ($a, $b) {
            $vendorA = getCardVendor($a['cardName'] ?? '');
            $vendorB = getCardVendor($b['cardName'] ?? '');
            $cmpVendor = strcasecmp($vendorA, $vendorB);
            if ($cmpVendor !== 0) {
                return $cmpVendor;
            }
            $valA = (float)($a['cardValue'] ?? 0);
            $valB = (float)($b['cardValue'] ?? 0);
            if ($valA != $valB) {
                return ($valA < $valB) ? -1 : 1;
            }
            $ptsA = (int)($a['points'] ?? 0);
            $ptsB = (int)($b['points'] ?? 0);
            return $ptsA <=> $ptsB;
        });
    }
}

if (!function_exists('getCardImage')) {
    /**
     * Helper to resolve card image path based on card name
     */
    function getCardImage(?string $cardName): string {
        if (!$cardName) {
            return 'images/card-airasia.jpg';
        }
        $name = strtolower($cardName);
        if (str_contains($name, 'baggage') || str_contains($name, 'seat')) return 'images/card-airasia-baggage.jpg';
        if (str_contains($name, 'meal')) return 'images/card-airasia-meal.jpg';
        if (str_contains($name, 'amazon')) return 'images/card-amazon.jpg';
        if (str_contains($name, 'apple')) return 'images/card-apple.jpg';
        if (str_contains($name, 'best buy') || str_contains($name, 'bestbuy')) return 'images/card-bestbuy.jpg';
        if (str_contains($name, 'google')) return 'images/card-googleplay.jpg';
        if (str_contains($name, 'grab')) return 'images/card-grab.jpg';
        if (str_contains($name, 'hilton')) return 'images/card-hilton.jpg';
        if (str_contains($name, 'marriott')) return 'images/card-marriott.jpg';
        if (str_contains($name, 'agoda')) return 'images/card-agoda.jpg';
        if (str_contains($name, 'hotel')) return 'images/card-hotel.jpg';
        if (str_contains($name, 'netflix')) return 'images/card-netflix.jpg';
        if (str_contains($name, 'sephora')) return 'images/card-sephora.jpg';
        if (str_contains($name, 'spotify')) return 'images/card-spotify.jpg';
        if (str_contains($name, 'starbucks')) return 'images/card-starbucks.jpg';
        if (str_contains($name, 'target')) return 'images/card-target.jpg';
        if (str_contains($name, 'airasia') || str_contains($name, 'flight')) return 'images/card-airasia.jpg';
        return 'images/card-airasia.jpg';
    }
}
