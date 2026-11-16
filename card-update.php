<?php
$pageTitle = 'Update Gift Card - AirAsia Rewards';
require_once 'includes/header.php';
require_once 'includes/nav.php';

$errors = [];

// Specific to update: validate and retrieve target cardId (supports cardId and card_id)
$rawCardId = $_POST['cardId'] ?? $_GET['cardId'] ?? $_POST['card_id'] ?? $_GET['card_id'] ?? null;
$cardId = filter_var($rawCardId, FILTER_VALIDATE_INT) ?: null;

if (!$cardId) {
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    header('Location: card-list.php');
    exit;
}

// Fetch existing card record from database
$stmt = $pdo->prepare('SELECT * FROM GiftCard WHERE cardId = :cardId');
$stmt->execute([':cardId' => $cardId]);
$card = $stmt->fetch();

if (!$card) {
    // Fail on non-existent card by redirecting to main page
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    header('Location: card-list.php');
    exit;
}

// Initial values from database record
$cardName = $card['cardName'];
$cardType = $card['cardType'];
$cardValueRaw = number_format((float)$card['cardValue'], 2, '.', '');
$pointsRaw = (string)$card['points'];
$actualCardId = (int)$card['cardId'];

// Specific to update: process form submission
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $cardName = trim($_POST['cardName'] ?? '');
    $cardType = trim($_POST['cardType'] ?? '');
    $cardValueRaw = trim($_POST['cardValue'] ?? '');
    $pointsRaw = trim($_POST['points'] ?? '');

    // Execute generalized validation logic
    $validation = validateGiftCardData($_POST, $_FILES['cardImage'] ?? null);
    $errors = $validation['errors'];

    // Update with prepared statement when validation succeeds
    if (empty($errors)) {
        $data = $validation['data'];

        $updateStmt = $pdo->prepare('UPDATE GiftCard SET cardName = :cardName, cardType = :cardType, cardValue = :cardValue, points = :points WHERE cardId = :cardId');
        $updateStmt->execute([
            ':cardName'  => $data['cardName'],
            ':cardType'  => $data['cardType'],
            ':cardValue' => $data['cardValue'],
            ':points'    => $data['points'],
            ':cardId'    => $cardId
        ]);

        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        // Redirect to card-details page to display updated information with success alert
        header('Location: card-details.php?cardId=' . $cardId . '&updated=1');
        exit;
    }
}

$cardImage = getCardImage($cardName);
?>

    <!-- Main Content Area: General Wireframe Structure -->
    <main class="container py-4 py-md-5 my-auto">
        <!-- Outer Frame Wrapper -->
        <div class="outer-frame">
            <!-- Top Controls Row -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <a href="card-details.php?cardId=<?php echo htmlspecialchars((string)$actualCardId); ?>" class="btn btn-outline-secondary btn-sm" id="btnGoBack">
                    <i class="bi bi-arrow-left me-1"></i> GO BACK
                </a>
            </div>

            <!-- Centered Header Box: UPDATE CARD -->
            <div class="text-center mb-4 mb-md-5">
                <div class="page-header-box">
                    <h1 class="page-header-title">Update Card</h1>
                </div>
            </div>

            <!-- Server-Side Error Messages Alert (Rendered via shared helper) -->
            <?php echo renderValidationAlert($errors); ?>

            <!-- Update Gift Card Form -->
            <form action="card-update.php" method="POST" id="updateCardForm" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="cardId" value="<?php echo htmlspecialchars((string)$actualCardId); ?>">

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
                                    <input type="file" 
                                           class="form-control <?php echo getFieldValidationClass($errors, 'cardImage'); ?>" 
                                           id="cardImage" 
                                           name="cardImage" 
                                           accept="image/*">
                                    <?php echo renderFieldFeedback($errors, 'cardImage'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Gift Card Fields with Repopulation -->
                    <div class="col-12 col-md-7 col-lg-6">
                        <div class="card card-reactive h-100">
                            <div class="card-body p-4 p-md-5">
                                <!-- Card Name Field -->
                                <div class="mb-3">
                                    <label for="cardName" class="form-label">Card Name</label>
                                    <input type="text" 
                                           class="form-control <?php echo getFieldValidationClass($errors, 'cardName'); ?>" 
                                           id="cardName" 
                                           name="cardName" 
                                           value="<?php echo htmlspecialchars($cardName); ?>" 
                                           required>
                                    <?php echo renderFieldFeedback($errors, 'cardName'); ?>
                                </div>

                                <!-- Card Type Field -->
                                <div class="mb-3">
                                    <label for="cardType" class="form-label">Card Type</label>
                                    <select class="form-select <?php echo getFieldValidationClass($errors, 'cardType'); ?>" 
                                            id="cardType" 
                                            name="cardType" 
                                            required>
                                        <?php foreach (getValidCardTypes() as $type): ?>
                                            <option value="<?php echo htmlspecialchars($type); ?>" <?php echo ($cardType === $type) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($type); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo renderFieldFeedback($errors, 'cardType'); ?>
                                </div>

                                <!-- Cost to Redeem / Points Field -->
                                <div class="mb-3">
                                    <label for="points" class="form-label">Cost to Redeem (Points Required)</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text">
                                            <i class="bi bi-stars text-warning"></i>
                                        </span>
                                        <input type="number" 
                                               class="form-control <?php echo getFieldValidationClass($errors, 'points'); ?>" 
                                               id="points" 
                                               name="points" 
                                               value="<?php echo htmlspecialchars($pointsRaw); ?>" 
                                               min="1" 
                                               required>
                                        <?php echo renderFieldFeedback($errors, 'points'); ?>
                                    </div>
                                </div>

                                <!-- Card Value Field -->
                                <div class="mb-3">
                                    <label for="cardValue" class="form-label">Card Value</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control <?php echo getFieldValidationClass($errors, 'cardValue'); ?>" 
                                               id="cardValue" 
                                               name="cardValue" 
                                               value="<?php echo htmlspecialchars($cardValueRaw); ?>" 
                                               min="0.01" 
                                               required>
                                        <?php echo renderFieldFeedback($errors, 'cardValue'); ?>
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
                    <a href="card-details.php?cardId=<?php echo htmlspecialchars((string)$actualCardId); ?>" class="btn btn-outline-secondary px-4 py-2 fs-6" id="btnCancel">
                        CANCEL
                    </a>
                </div>
            </form>
        </div>
    </main>

<?php
require_once 'includes/footer.php';
?>
