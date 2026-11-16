<?php
$pageTitle = 'Update Gift Card - AirAsia Rewards';
require_once 'includes/header.php';
require_once 'includes/nav.php';

// Process update submission with PDO prepared statement
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $cardId = filter_input(INPUT_POST, 'cardId', FILTER_VALIDATE_INT);
    $cardName = trim($_POST['cardName'] ?? '');
    $cardType = trim($_POST['cardType'] ?? '');
    $cardValue = filter_input(INPUT_POST, 'cardValue', FILTER_VALIDATE_FLOAT) ?: 0.0;
    $points = filter_input(INPUT_POST, 'points', FILTER_VALIDATE_INT) ?: 0;

    if ($cardId && !empty($cardName) && !empty($cardType)) {
        $stmt = $pdo->prepare('UPDATE GiftCard SET cardName = :cardName, cardType = :cardType, cardValue = :cardValue, points = :points WHERE cardId = :cardId');
        $stmt->execute([
            ':cardName'  => $cardName,
            ':cardType'  => $cardType,
            ':cardValue' => $cardValue,
            ':points'    => $points,
            ':cardId'    => $cardId
        ]);
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Location: card-list.php');
        exit;
    }
}

// Fetch existing card using prepared statement receiving value from URL parameter
$cardId = filter_input(INPUT_GET, 'cardId', FILTER_VALIDATE_INT) ?? 1;
$stmt = $pdo->prepare('SELECT * FROM GiftCard WHERE cardId = :cardId');
$stmt->execute([':cardId' => $cardId]);
$card = $stmt->fetch();

if (!$card) {
    // Fallback to first available card if not found
    $card = $pdo->query('SELECT * FROM GiftCard ORDER BY cardId ASC LIMIT 1')->fetch();
}

$cardName = $card ? $card['cardName'] : '';
$cardType = $card ? $card['cardType'] : '';
$cardValue = $card ? (float)$card['cardValue'] : 0.0;
$points = $card ? (int)$card['points'] : 0;
$actualCardId = $card ? $card['cardId'] : 1;
$cardImage = getCardImage($cardName);
?>

    <!-- Main Content Area: General Wireframe Structure -->
    <main class="container py-4 py-md-5 my-auto">
        <!-- Outer Frame Wrapper -->
        <div class="outer-frame">
            <!-- Top Controls Row -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <a href="card-details.php?cardId=<?php echo htmlspecialchars($actualCardId); ?>" class="btn btn-outline-secondary btn-sm" id="btnGoBack">
                    <i class="bi bi-arrow-left me-1"></i> GO BACK
                </a>
            </div>

            <!-- Centered Header Box: UPDATE CARD -->
            <div class="text-center mb-4 mb-md-5">
                <div class="page-header-box">
                    <h1 class="page-header-title">Update Card</h1>
                </div>
            </div>

            <!-- Update Gift Card Form -->
            <form action="card-update.php" method="POST" id="updateCardForm" enctype="multipart/form-data">
                <input type="hidden" name="cardId" value="<?php echo htmlspecialchars($actualCardId); ?>">

                <div class="row g-4 justify-content-center align-items-stretch">
                    <!-- Left Column: [[GIFT CARD IMAGE]] & Upload Image -->
                    <div class="col-12 col-md-5 col-lg-5">
                        <div class="card card-reactive h-100">
                            <div class="card-body d-flex flex-column justify-content-center p-4">
                                <div class="mb-3 text-center">
                                    <img src="<?php echo htmlspecialchars($cardImage); ?>" class="img-fluid rounded shadow-sm mb-2" alt="<?php echo htmlspecialchars($cardName); ?>" style="max-height: 200px; width: 100%; object-fit: cover;">
                                    <span class="text-muted small d-block">Current Card Image</span>
                                </div>
                                <div class="mt-2">
                                    <label for="cardImage" class="form-label fw-semibold">Upload New Image</label>
                                    <input type="file" class="form-control" id="cardImage" name="cardImage" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Gift Card Fields -->
                    <div class="col-12 col-md-7 col-lg-6">
                        <div class="card card-reactive h-100">
                            <div class="card-body p-4 p-md-5">
                                <!-- Card Name Field -->
                                <div class="mb-3">
                                    <label for="cardName" class="form-label">Card Name</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="cardName" 
                                           name="cardName" 
                                           value="<?php echo htmlspecialchars($cardName); ?>" 
                                           required>
                                </div>

                                <!-- Card Type Field -->
                                <div class="mb-3">
                                    <label for="cardType" class="form-label">Card Type</label>
                                    <select class="form-select" id="cardType" name="cardType" required>
                                        <?php 
                                        $types = ['Travel', 'Shopping', 'Electronics', 'Food & Dining', 'Food & Beverage', 'Transportation', 'Travel & Lodging', 'Entertainment', 'Retail'];
                                        foreach ($types as $type): 
                                        ?>
                                            <option value="<?php echo $type; ?>" <?php echo ($cardType === $type) ? 'selected' : ''; ?>>
                                                <?php echo $type; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Cost to Redeem / Points Field -->
                                <div class="mb-3">
                                    <label for="points" class="form-label">Cost to Redeem (Points Required)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-stars text-warning"></i>
                                        </span>
                                        <input type="number" 
                                               class="form-control" 
                                               id="points" 
                                               name="points" 
                                               value="<?php echo htmlspecialchars($points); ?>" 
                                               min="1" 
                                               required>
                                    </div>
                                </div>

                                <!-- Card Value Field -->
                                <div class="mb-3">
                                    <label for="cardValue" class="form-label">Card Value</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control" 
                                               id="cardValue" 
                                               name="cardValue" 
                                               value="<?php echo htmlspecialchars(number_format($cardValue, 2, '.', '')); ?>" 
                                               min="0" 
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Action Buttons: UPDATE CARD & CANCEL -->
                <div class="d-flex flex-wrap justify-content-center gap-3 mt-5 pt-3">
                    <button type="submit" class="btn btn-airasia px-4 py-2 fs-6" id="btnUpdateCard">
                        UPDATE CARD
                    </button>
                    <a href="card-details.php?cardId=<?php echo htmlspecialchars($actualCardId); ?>" class="btn btn-outline-secondary px-4 py-2 fs-6" id="btnCancel">
                        CANCEL
                    </a>
                </div>
            </form>
        </div>
    </main>

<?php
require_once 'includes/footer.php';
?>
