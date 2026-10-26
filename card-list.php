<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Available Gift Cards - AirAsia Rewards</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@latest/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <!-- Top Navigation Bar with Left-Side Framing & Right-Side Actions -->
    <header class="top-navbar">
        <div class="container">
            <nav class="navbar navbar-expand-lg p-0">
                <a href="card-list.php" class="navbar-brand-framing" aria-label="AirAsia Home">
                    <img src="images/airasia_nobackground.jpg" alt="AirAsia Logo" class="brand-logo">
                    <div class="brand-text">
                        <span class="brand-subtitle">Rewards Program</span>
                    </div>
                </a>

                <!-- Nav Actions matching wireframe (Welcome message, Add a Card, Add a Customer, Log Out) -->
                <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 gap-md-3 ms-auto mt-2 mt-md-0">
                    <span class="nav-user-greeting d-none d-sm-inline-flex">
                        <i class="bi bi-person-circle"></i>
                        <span>Welcome, <span class="nav-user-name">Member</span></span>
                    </span>
                    <a href="card-add.php" class="btn-nav-action btn-nav-admin" id="navAddCard" title="Administrative: Add a new gift card">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>ADD A CARD</span>
                    </a>
                    <a href="#" class="btn-nav-action" id="navAddCustomer" title="Add a customer">
                        <i class="bi bi-person-plus"></i>
                        <span>ADD A CUSTOMER</span>
                    </a>
                    <a href="login.php" class="btn-nav-logout" id="navLogout" title="Log out of system">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        <span>LOG OUT</span>
                    </a>
                </div>
            </nav>
        </div>
    </header>

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
                <!-- Card 1: AirAsia Flight Voucher -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card card-reactive gift-card h-100">
                        <a href="card-details.php" class="card-img-link" aria-label="View AirAsia Flight Voucher details">
                            <img src="images/card-airasia.jpg" class="gift-card-img" alt="AirAsia Flight Voucher">
                        </a>
                        <div class="card-body d-flex flex-column p-4">
                            <h2 class="h5 mb-2">
                                <a href="card-details.php" class="gift-card-title-link">AirAsia Flight Voucher</a>
                            </h2>
                            <p class="text-muted small mb-3">Redeemable for all AirAsia domestic and international flights.</p>
                            <div class="mt-auto d-flex align-items-center justify-content-between pt-2">
                                <span class="points-badge">
                                    <i class="bi bi-stars"></i>
                                    <span>5,000 points</span>
                                </span>
                                <a href="card-details.php" class="btn-outline-airasia">
                                    <span>Redeem</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Amazon Gift Card -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card card-reactive gift-card h-100">
                        <a href="card-details.php" class="card-img-link" aria-label="View Amazon Gift Card details">
                            <img src="images/card-amazon.jpg" class="gift-card-img" alt="Amazon Gift Card">
                        </a>
                        <div class="card-body d-flex flex-column p-4">
                            <h2 class="h5 mb-2">
                                <a href="card-details.php" class="gift-card-title-link">Amazon Gift Card</a>
                            </h2>
                            <p class="text-muted small mb-3">Shop millions of items with digital Amazon shopping balance.</p>
                            <div class="mt-auto d-flex align-items-center justify-content-between pt-2">
                                <span class="points-badge">
                                    <i class="bi bi-stars"></i>
                                    <span>2,500 points</span>
                                </span>
                                <a href="card-details.php" class="btn-outline-airasia">
                                    <span>Redeem</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Apple Store Gift Card -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card card-reactive gift-card h-100">
                        <a href="card-details.php" class="card-img-link" aria-label="View Apple Gift Card details">
                            <img src="images/card-apple.jpg" class="gift-card-img" alt="Apple Gift Card">
                        </a>
                        <div class="card-body d-flex flex-column p-4">
                            <h2 class="h5 mb-2">
                                <a href="card-details.php" class="gift-card-title-link">Apple Gift Card</a>
                            </h2>
                            <p class="text-muted small mb-3">Redeem for products, accessories, apps, games, music, and iCloud.</p>
                            <div class="mt-auto d-flex align-items-center justify-content-between pt-2">
                                <span class="points-badge">
                                    <i class="bi bi-stars"></i>
                                    <span>2,500 points</span>
                                </span>
                                <a href="card-details.php" class="btn-outline-airasia">
                                    <span>Redeem</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Grab Rides & Food -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card card-reactive gift-card h-100">
                        <a href="card-details.php" class="card-img-link" aria-label="View Grab Voucher details">
                            <img src="images/card-grab.jpg" class="gift-card-img" alt="Grab Rides & Food">
                        </a>
                        <div class="card-body d-flex flex-column p-4">
                            <h2 class="h5 mb-2">
                                <a href="card-details.php" class="gift-card-title-link">Grab Rides & Food</a>
                            </h2>
                            <p class="text-muted small mb-3">Convenient transport and doorstep meal delivery across Southeast Asia.</p>
                            <div class="mt-auto d-flex align-items-center justify-content-between pt-2">
                                <span class="points-badge">
                                    <i class="bi bi-stars"></i>
                                    <span>1,000 points</span>
                                </span>
                                <a href="card-details.php" class="btn-outline-airasia">
                                    <span>Redeem</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 5: Luxury Hotel Stay -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card card-reactive gift-card h-100">
                        <a href="card-details.php" class="card-img-link" aria-label="View Luxury Hotel Stay details">
                            <img src="images/card-hotel.jpg" class="gift-card-img" alt="Luxury Hotel Stay">
                        </a>
                        <div class="card-body d-flex flex-column p-4">
                            <h2 class="h5 mb-2">
                                <a href="card-details.php" class="gift-card-title-link">Luxury Hotel Stay</a>
                            </h2>
                            <p class="text-muted small mb-3">Redeemable towards top partner resorts and boutique hotels worldwide.</p>
                            <div class="mt-auto d-flex align-items-center justify-content-between pt-2">
                                <span class="points-badge">
                                    <i class="bi bi-stars"></i>
                                    <span>10,000 points</span>
                                </span>
                                <a href="card-details.php" class="btn-outline-airasia">
                                    <span>Redeem</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 6: Starbucks Coffee -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card card-reactive gift-card h-100">
                        <a href="card-details.php" class="card-img-link" aria-label="View Starbucks Gift Card details">
                            <img src="images/card-starbucks.jpg" class="gift-card-img" alt="Starbucks Gift Card">
                        </a>
                        <div class="card-body d-flex flex-column p-4">
                            <h2 class="h5 mb-2">
                                <a href="card-details.php" class="gift-card-title-link">Starbucks Coffee Card</a>
                            </h2>
                            <p class="text-muted small mb-3">Enjoy handcrafted beverages, fresh brewed roasts, and tasty treats.</p>
                            <div class="mt-auto d-flex align-items-center justify-content-between pt-2">
                                <span class="points-badge">
                                    <i class="bi bi-stars"></i>
                                    <span>1,000 points</span>
                                </span>
                                <a href="card-details.php" class="btn-outline-airasia">
                                    <span>Redeem</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer matching wireframe -->
    <footer class="app-footer">
        <div class="container">
            <p class="mb-0">&copy; Air Asia Rewards Program. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
