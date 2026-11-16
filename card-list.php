<?php
$pageTitle = 'Available Gift Cards - AirAsia Rewards';
require_once 'includes/header.php';
require_once 'includes/nav.php';

// Cards sorted to group like vendors together and sub-sorted by value (lowest -> highest)
$cardType = filter_input(INPUT_GET, 'cardType', FILTER_DEFAULT);
if (!empty($cardType)) {
    $stmt = $pdo->prepare('SELECT * FROM GiftCard WHERE cardType = :cardType ORDER BY card_vendor(cardName) ASC, cardValue ASC, points ASC');
    $stmt->execute([':cardType' => $cardType]);
    $cards = $stmt->fetchAll();
} else {
    $stmt = $pdo->query('SELECT * FROM GiftCard ORDER BY card_vendor(cardName) ASC, cardValue ASC, points ASC');
    $cards = $stmt->fetchAll();
}
sortCardsByVendorAndValue($cards);
?>

    <!-- Main Content Area: Wireframe Structure -->
    <main class="container py-4 py-md-5">
        <!-- Outer Frame Wrapper -->
        <div class="outer-frame">
            <!-- Centered Header Box (matching wireframe) -->
            <div class="text-center mb-4 mb-md-5">
                <div class="page-header-box">
                    <h1 class="page-header-title">Available Gift Cards</h1>
                </div>
            </div>

            <!-- Gift Cards Grid -->
            <div class="row g-4">
                <?php if (!empty($cards)): ?>
                    <?php foreach ($cards as $card): ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card card-reactive gift-card h-100">
                                <a href="card-details.php?cardId=<?php echo htmlspecialchars($card['cardId']); ?>" class="card-img-link" aria-label="View <?php echo htmlspecialchars($card['cardName']); ?> details">
                                    <img src="<?php echo htmlspecialchars(getCardImage($card['cardName'])); ?>" class="gift-card-img" alt="<?php echo htmlspecialchars($card['cardName']); ?>">
                                </a>
                                <div class="card-body d-flex flex-column p-4">
                                    <h2 class="h5 mb-2">
                                        <a href="card-details.php?cardId=<?php echo htmlspecialchars($card['cardId']); ?>" class="gift-card-title-link">
                                            <?php echo htmlspecialchars($card['cardName']); ?>
                                        </a>
                                    </h2>
                                    <p class="text-muted small mb-3"><?php echo htmlspecialchars($card['cardType']); ?> &bull; Value: $<?php echo number_format($card['cardValue'], 2); ?></p>
                                    <div class="mt-auto d-flex align-items-center justify-content-between pt-2">
                                        <span class="points-badge">
                                            <i class="bi bi-stars"></i>
                                            <span><?php echo number_format($card['points']); ?> points</span>
                                        </span>
                                        <a href="card-details.php?cardId=<?php echo htmlspecialchars($card['cardId']); ?>" class="btn-outline-airasia">
                                            <span>Redeem</span>
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted fs-5">No gift cards are currently available.</p>
                        <a href="card-add.php" class="btn btn-airasia">Add a New Gift Card</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php
require_once 'includes/footer.php';
?>
