<?php
$pageTitle = 'Add Gift Card - AirAsia Rewards';
require_once 'includes/header.php';
require_once 'includes/nav.php';

$errors = [];
$cardName = '';
$cardType = '';
$cardValueRaw = '';
$pointsRaw = '';

// Process form submission with shared validation and PDO prepared statement
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $cardName = trim($_POST['cardName'] ?? '');
    $cardType = trim($_POST['cardType'] ?? '');
    $cardValueRaw = trim($_POST['cardValue'] ?? '');
    $pointsRaw = trim($_POST['points'] ?? '');

    // Execute generalized validation logic
    $validation = validateGiftCardData($_POST, $_FILES['cardImage'] ?? null);
    $errors = $validation['errors'];

    // Insert with prepared statement when validation succeeds
    if (empty($errors)) {
        $data = $validation['data'];

        $stmt = $pdo->prepare('INSERT INTO GiftCard (cardName, cardType, cardValue, points) VALUES (:cardName, :cardType, :cardValue, :points)');
        $stmt->execute([
            ':cardName'  => $data['cardName'],
            ':cardType'  => $data['cardType'],
            ':cardValue' => $data['cardValue'],
            ':points'    => $data['points']
        ]);

        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Location: card-list.php');
        exit;
    }
}
?>

    <!-- Main Content Area: General Wireframe Structure -->
    <main class="container py-4 py-md-5 my-auto">
        <!-- Outer Frame Wrapper -->
        <div class="outer-frame">
            <!-- Top Controls Row (Go Back link matching wireframe) -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <a href="card-list.php" class="btn btn-outline-secondary btn-sm" id="btnGoBack">
                    <i class="bi bi-arrow-left me-1"></i> GO BACK
                </a>
            </div>

            <!-- Centered Header Box: ADD A GIFT CARD -->
            <div class="text-center mb-4 mb-md-5">
                <div class="page-header-box">
                    <h1 class="page-header-title">Add a Gift Card</h1>
                </div>
            </div>

            <!-- Server-Side Error Messages Alert (Rendered via shared helper) -->
            <?php echo renderValidationAlert($errors); ?>

            <!-- Add Gift Card Form -->
            <form action="card-add.php" method="POST" id="addCardForm" enctype="multipart/form-data" novalidate>
                <div class="row g-4 justify-content-center align-items-stretch">
                    <!-- Left Column: [[GIFT CARD IMAGE]] & Upload Image -->
                    <div class="col-12 col-md-5 col-lg-5">
                        <div class="card card-reactive h-100">
                            <div class="card-body d-flex flex-column justify-content-center p-4">
                                <div class="image-upload-box mb-3">
                                    <i class="bi bi-image fs-1 text-muted mb-2 d-block"></i>
                                    <span class="fw-semibold text-dark d-block mb-1">Gift Card Image</span>
                                    <span class="text-muted small">PNG or JPG up to 5MB</span>
                                </div>
                                <div class="mt-2">
                                    <label for="cardImage" class="form-label fw-semibold">Upload Image</label>
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

                    <!-- Right Column: Required Gift Card Fields with Repopulation -->
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
                                           placeholder="e.g. AirAsia Flight Voucher" 
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
                                        <option value="" disabled <?php echo empty($cardType) ? 'selected' : ''; ?>>Select a card type...</option>
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
                                               placeholder="e.g. 1000" 
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
                                               placeholder="0.00" 
                                               min="0.01" 
                                               required>
                                        <?php echo renderFieldFeedback($errors, 'cardValue'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Action Buttons: ADD CARD & CANCEL -->
                <div class="d-flex flex-wrap justify-content-center gap-3 mt-5 pt-3">
                    <button type="submit" class="btn btn-airasia px-4 py-2 fs-6" id="btnAddCard">
                        <i class="bi bi-plus-circle me-1"></i> ADD CARD
                    </button>
                    <a href="card-list.php" class="btn btn-outline-secondary px-4 py-2 fs-6" id="btnCancel">
                        CANCEL
                    </a>
                </div>
            </form>
        </div>
    </main>

<?php
require_once 'includes/footer.php';
?>
