<?php
require_once 'includes/db.php';

// Fetch cardId from URL parameter
$rawCardId = $_GET['cardId'] ?? null;
$cardId = filter_var($rawCardId, FILTER_VALIDATE_INT) ?: null;

if ($cardId) {
    // Delete card using PDO prepared statement
    $stmt = $pdo->prepare('DELETE FROM GiftCard WHERE cardId = :cardId');
    $stmt->execute([':cardId' => $cardId]);
}

// Redirect back to gift cards list
header('Location: card-list.php');
exit;
