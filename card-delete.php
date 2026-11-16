<?php
require_once 'includes/db.php';

// Fetch cardId from URL parameter
$cardId = filter_input(INPUT_GET, 'cardId', FILTER_VALIDATE_INT);

if ($cardId) {
    // Delete card using PDO prepared statement
    $stmt = $pdo->prepare('DELETE FROM GiftCard WHERE cardId = :cardId');
    $stmt->execute([':cardId' => $cardId]);
}

// Redirect back to gift cards list
header('Location: card-list.php');
exit;
