<?php
$pageTitle = 'Update Gift Card - AirAsia Rewards';
require_once 'includes/header.php';
require_once 'includes/nav.php';

$errors = [];

// Determine cardId from POST or GET parameter
$rawCardId = $_POST['cardId'] ?? $_GET['cardId'] ?? null;
$cardId = filter_var($rawCardId, FILTER_VALIDATE_INT) ?: null;

if (!$cardId) {
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    header('Location: card-list.php');
    exit;
}

// Fetch existing card record
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

// Process update submission with PDO prepared statement
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

    // Optional image validation
    if (isset($_FILES['cardImage']) && $_FILES['cardImage']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['cardImage']['error'] !== UPLOAD_ERR_OK) {
            $errors['cardImage'] = 'Error uploading image. Please try again.';
        } elseif ($_FILES['cardImage']['size'] > 5 * 1024 * 1024) {
            $errors['cardImage'] = 'Gift card image must be 5MB or smaller.';
        }
    }

    // When validation succeeds, execute prepared statement
    if (empty($errors)) {
        $cardValue = (float)$cardValueRaw;
        $points = (int)$pointsRaw;

        $updateStmt = $pdo->prepare('UPDATE GiftCard SET cardName = :cardName, cardType = :cardType, cardValue = :cardValue, points = :points WHERE cardId = :cardId');
        $updateStmt->execute([
            ':cardName'  => $cardName,
            ':cardType'  => $cardType,
            ':cardValue' => $cardValue,
            ':points'    => $points,
            ':cardId'    => $cardId
        ]);

        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        // Redirect to card-details page to display updated information
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

                    <!-- Right Column: Gift Card Fields with Repopulation -->
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
