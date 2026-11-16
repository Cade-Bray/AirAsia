<?php
$pageTitle = 'Add Gift Card - AirAsia Rewards';
require_once 'includes/header.php';
require_once 'includes/nav.php';

$errors = [];
$cardName = '';
$cardType = '';
$cardValueRaw = '';
$pointsRaw = '';

// Process form submission with PDO prepared statement
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $cardName = trim($_POST['cardName'] ?? '');
    $cardType = trim($_POST['cardType'] ?? '');
    $cardValueRaw = trim($_POST['cardValue'] ?? '');
    $pointsRaw = trim($_POST['points'] ?? '');

    // Server-side validation
    if ($cardName === '') {
        $errors['cardName'] = 'Card Name is required.';
    } elseif (strlen($cardName) > 100) {
        $errors['cardName'] = 'Card Name cannot exceed 100 characters.';
    }

    $validTypes = [
        'Travel', 'Shopping', 'Electronics', 'Food & Dining', 
        'Food & Beverage', 'Transportation', 'Travel & Lodging', 
        'Entertainment', 'Retail'
    ];
    if ($cardType === '') {
        $errors['cardType'] = 'Please select a Card Type.';
    } elseif (!in_array($cardType, $validTypes, true)) {
        $errors['cardType'] = 'Invalid Card Type selected.';
    }

    if ($pointsRaw === '') {
        $errors['points'] = 'Cost to redeem (Points Required) is required.';
    } elseif (!filter_var($pointsRaw, FILTER_VALIDATE_INT) || (int)$pointsRaw < 1) {
        $errors['points'] = 'Points must be a positive integer greater than 0.';
    }

    if ($cardValueRaw === '') {
        $errors['cardValue'] = 'Card Value is required.';
    } elseif (!is_numeric($cardValueRaw) || (float)$cardValueRaw <= 0) {
        $errors['cardValue'] = 'Card Value must be a valid dollar amount greater than $0.00.';
    }

    // Optional image validation if an image was uploaded
    if (isset($_FILES['cardImage']) && $_FILES['cardImage']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['cardImage']['error'] !== UPLOAD_ERR_OK) {
            $errors['cardImage'] = 'Error uploading image. Please try again.';
        } elseif ($_FILES['cardImage']['size'] > 5 * 1024 * 1024) {
            $errors['cardImage'] = 'Gift card image must be 5MB or smaller.';
        }
    }

    // Insert with prepared statement when validation succeeds
    if (empty($errors)) {
        $cardValue = (float)$cardValueRaw;
        $points = (int)$pointsRaw;

        $stmt = $pdo->prepare('INSERT INTO GiftCard (cardName, cardType, cardValue, points) VALUES (:cardName, :cardType, :cardValue, :points)');
        $stmt->execute([
            ':cardName'  => $cardName,
            ':cardType'  => $cardType,
            ':cardValue' => $cardValue,
            ':points'    => $points
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

            <!-- Server-Side Error Messages Alert -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                        <span class="fw-bold">Please correct the following errors:</span>
                    </div>
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

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
                                           class="form-control <?php echo isset($errors['cardImage']) ? 'is-invalid' : ''; ?>" 
                                           id="cardImage" 
                                           name="cardImage" 
                                           accept="image/*">
                                    <?php if (isset($errors['cardImage'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['cardImage']); ?></div>
                                    <?php endif; ?>
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
                                           class="form-control <?php echo isset($errors['cardName']) ? 'is-invalid' : ''; ?>" 
                                           id="cardName" 
                                           name="cardName" 
                                           value="<?php echo htmlspecialchars($cardName); ?>" 
                                           placeholder="e.g. AirAsia Flight Voucher" 
                                           required>
                                    <?php if (isset($errors['cardName'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['cardName']); ?></div>
                                    <?php endif; ?>
                                </div>

                                <!-- Card Type Field -->
                                <div class="mb-3">
                                    <label for="cardType" class="form-label">Card Type</label>
                                    <select class="form-select <?php echo isset($errors['cardType']) ? 'is-invalid' : ''; ?>" 
                                            id="cardType" 
                                            name="cardType" 
                                            required>
                                        <option value="" disabled <?php echo empty($cardType) ? 'selected' : ''; ?>>Select a card type...</option>
                                        <?php 
                                        $types = [
                                            'Travel', 'Shopping', 'Electronics', 'Food & Dining', 
                                            'Food & Beverage', 'Transportation', 'Travel & Lodging', 
                                            'Entertainment', 'Retail'
                                        ];
                                        foreach ($types as $type): 
                                        ?>
                                            <option value="<?php echo htmlspecialchars($type); ?>" <?php echo ($cardType === $type) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($type); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['cardType'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['cardType']); ?></div>
                                    <?php endif; ?>
                                </div>

                                <!-- Cost to Redeem / Points Field -->
                                <div class="mb-3">
                                    <label for="points" class="form-label">Cost to Redeem (Points Required)</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text">
                                            <i class="bi bi-stars text-warning"></i>
                                        </span>
                                        <input type="number" 
                                               class="form-control <?php echo isset($errors['points']) ? 'is-invalid' : ''; ?>" 
                                               id="points" 
                                               name="points" 
                                               value="<?php echo htmlspecialchars($pointsRaw); ?>" 
                                               placeholder="e.g. 1000" 
                                               min="1" 
                                               required>
                                        <?php if (isset($errors['points'])): ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['points']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Card Value Field -->
                                <div class="mb-3">
                                    <label for="cardValue" class="form-label">Card Value</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control <?php echo isset($errors['cardValue']) ? 'is-invalid' : ''; ?>" 
                                               id="cardValue" 
                                               name="cardValue" 
                                               value="<?php echo htmlspecialchars($cardValueRaw); ?>" 
                                               placeholder="0.00" 
                                               min="0.01" 
                                               required>
                                        <?php if (isset($errors['cardValue'])): ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['cardValue']); ?></div>
                                        <?php endif; ?>
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
