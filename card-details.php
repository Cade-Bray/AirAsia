<?php
$pageTitle = 'Apple Gift Card Details - AirAsia Rewards';
require_once 'includes/header.php';
require_once 'includes/nav.php';
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

            <!-- Centered Header Box: [[GIFT CARD NAME]] DETAILS -->
            <div class="text-center mb-4 mb-md-5">
                <div class="page-header-box">
                    <h1 class="page-header-title">Apple Gift Card Details</h1>
                </div>
            </div>

            <!-- Two-Column Layout from Wireframe: Image/Redeem + Attributes -->
            <div class="row g-4 justify-content-center align-items-stretch">
                <!-- Left Column: [[GIFT CARD IMAGE]] & REDEEM Button -->
                <div class="col-12 col-md-5 col-lg-5">
                    <div class="card card-reactive h-100 overflow-hidden">
                        <div class="p-3 text-center bg-light">
                            <img src="images/card-apple.jpg" class="img-fluid rounded shadow-sm detail-card-img" alt="Apple Gift Card">
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center p-4">
                            <button type="button" class="btn btn-airasia w-100 py-2 fs-5" id="btnRedeem">
                                <i class="bi bi-bag-check-fill me-2"></i> REDEEM
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column: [[GIFT CARD NAME]] Details & Metadata -->
                <div class="col-12 col-md-7 col-lg-6">
                    <div class="card card-reactive h-100">
                        <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-between">
                            <div>
                                <h2 class="card-title h3 fw-bold text-dark mb-2">Apple Gift Card</h2>
                                <p class="text-muted mb-4">Redeem for products, accessories, apps, games, music, and iCloud.</p>

                                <div class="list-group list-group-flush border-top border-bottom my-3">
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-0">
                                        <span class="text-muted fw-semibold">CARD TYPE:</span>
                                        <span class="fw-bold text-dark">Digital / Retail</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-0">
                                        <span class="text-muted fw-semibold">CARD VALUE:</span>
                                        <span class="fw-bold text-dark fs-5">$25.00</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-0">
                                        <span class="text-muted fw-semibold">COST TO REDEEM:</span>
                                        <span class="points-badge">
                                            <i class="bi bi-stars"></i>
                                            <span>2,500 points</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="small text-muted mt-3">
                                <i class="bi bi-info-circle me-1"></i> Points will be deducted immediately upon redemption confirmation.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Administrative Action Links Row (Wireframe: CARD UPDATE & DELETE CARD) -->
            <div class="d-flex flex-wrap justify-content-center gap-3 mt-5 pt-3">
                <a href="card-update.php" class="btn-nav-action btn-nav-admin px-4 py-2 fs-6" id="btnCardUpdate">
                    <i class="bi bi-pencil-square me-1"></i> CARD UPDATE
                </a>
                <a href="#" class="btn-nav-action btn-nav-danger px-4 py-2 fs-6" id="btnDeleteCard">
                    <i class="bi bi-trash3 me-1"></i> DELETE CARD
                </a>
            </div>
        </div>
    </main>

<?php
require_once 'includes/footer.php';
?>
