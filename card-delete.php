<?php
$pageTitle = 'Delete Gift Card - AirAsia Rewards';
require_once 'includes/header.php';
require_once 'includes/nav.php';

// Validate cardId parameter from POST or GET (supports cardId and card_id)
$rawCardId = $_POST['cardId'] ?? $_GET['cardId'] ?? $_POST['card_id'] ?? $_GET['card_id'] ?? null;
$cardId = validateCardId($rawCardId);

if (!$cardId) {
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    header('Location: card-list.php');
    exit;
}

// Retrieve selected Gift Card using prepared statement
$stmt = $pdo->prepare('SELECT * FROM GiftCard WHERE cardId = :cardId');
$stmt->execute([':cardId' => $cardId]);
$card = $stmt->fetch();

if (!$card) {
    // Fail safely if card does not exist
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    header('Location: card-list.php');
    exit;
}

// Handle confirmed deletion via POST request only
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $deleteStmt = $pdo->prepare('DELETE FROM GiftCard WHERE cardId = :cardId');
    $deleteStmt->execute([':cardId' => $cardId]);

    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    header('Location: card-list.php');
    exit;
}

// Display information identifying the card to be deleted
$cardName = $card['cardName'];
$cardType = $card['cardType'];
$cardValue = (float)$card['cardValue'];
$points = (int)$card['points'];
$actualCardId = (int)$card['cardId'];
$cardImage = getCardImage($cardName);
?>

    <!-- Main Content Area: Confirmation Screen Structure -->
    <main class="container py-4 py-md-5 my-auto">
        <!-- Outer Frame Wrapper -->
        <div class="outer-frame">
            <!-- Top Controls Row -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <a href="card-details.php?card_id=<?php echo htmlspecialchars((string)$actualCardId); ?>" class="btn btn-outline-secondary btn-sm" id="btnGoBack">
                    <i class="bi bi-arrow-left me-1"></i> GO BACK
                </a>
            </div>

            <!-- Centered Header Box: DELETE CARD -->
            <div class="text-center mb-4 mb-md-5">
                <div class="page-header-box">
                    <h1 class="page-header-title text-danger">Delete Gift Card</h1>
                </div>
            </div>

            <!-- Warning Confirmation Alert Banner -->
            <div class="alert alert-warning shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-3 me-3 text-danger"></i>
                    <div>
                        <strong class="d-block fs-6 text-dark">Are you sure you want to delete this gift card?</strong>
                        <span class="text-muted">This action is permanent and cannot be undone. The gift card will be completely removed from the database.</span>
                    </div>
                </div>
            </div>

            <!-- Clearly Identify the Gift Card to be Deleted -->
            <div class="card card-reactive border-danger shadow-sm mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4 align-items-center">
                        <div class="col-12 col-md-4 text-center">
                            <img src="<?php echo htmlspecialchars($cardImage); ?>" class="img-fluid rounded shadow-sm detail-card-img" alt="<?php echo htmlspecialchars($cardName); ?>" style="max-height: 180px; object-fit: cover;">
                        </div>
                        <div class="col-12 col-md-8">
                            <span class="badge bg-danger mb-2">Pending Deletion</span>
                            <h2 class="h4 fw-bold text-dark mb-2"><?php echo htmlspecialchars($cardName); ?></h2>
                            <p class="text-muted mb-3"><?php echo htmlspecialchars($cardType); ?> &bull; Gift Card #<?php echo htmlspecialchars((string)$actualCardId); ?></p>
                            
                            <div class="d-flex flex-wrap gap-4 pt-3 border-top">
                                <div>
                                    <span class="text-muted small d-block">CARD VALUE</span>
                                    <span class="fw-bold fs-5 text-dark">$<?php echo number_format($cardValue, 2); ?></span>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">COST TO REDEEM</span>
                                    <span class="points-badge mt-1">
                                        <i class="bi bi-stars"></i>
                                        <span><?php echo number_format($points); ?> points</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- POST Form Requiring Explicit Confirmation -->
            <form action="card-delete.php" method="POST" id="deleteCardForm">
                <input type="hidden" name="cardId" value="<?php echo htmlspecialchars((string)$actualCardId); ?>">

                <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                    <button type="submit" class="btn btn-danger px-4 py-2 fs-6" id="btnConfirmDelete">
                        <i class="bi bi-trash3-fill me-1"></i> CONFIRM DELETE
                    </button>
                    <a href="card-details.php?card_id=<?php echo htmlspecialchars((string)$actualCardId); ?>" class="btn btn-outline-secondary px-4 py-2 fs-6" id="btnCancel">
                        CANCEL
                    </a>
                </div>
            </form>
        </div>
    </main>

<?php
require_once 'includes/footer.php';
?>
