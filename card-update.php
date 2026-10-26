<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Gift Card - AirAsia Rewards</title>
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

    <!-- Main Content Area: General Wireframe Structure -->
    <main class="container py-4 py-md-5 my-auto">
        <!-- Outer Frame Wrapper -->
        <div class="outer-frame">
            <!-- Top Controls Row -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <a href="card-details.php" class="btn btn-outline-secondary btn-sm" id="btnGoBack">
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
            <form action="card-list.php" method="POST" id="updateCardForm" enctype="multipart/form-data">
                <div class="row g-4 justify-content-center align-items-stretch">
                    <!-- Left Column: [[GIFT CARD IMAGE]] & Upload Image -->
                    <div class="col-12 col-md-5 col-lg-5">
                        <div class="card card-reactive h-100">
                            <div class="card-body d-flex flex-column justify-content-center p-4">
                                <div class="mb-3 text-center">
                                    <img src="images/card-apple.jpg" class="img-fluid rounded shadow-sm mb-2" alt="Current Apple Gift Card Image" style="max-height: 200px; width: 100%; object-fit: cover;">
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
                                           value="Apple Gift Card" 
                                           required>
                                </div>

                                <!-- Card Type Field -->
                                <div class="mb-3">
                                    <label for="cardType" class="form-label">Card Type</label>
                                    <select class="form-select" id="cardType" name="cardType" required>
                                        <option value="Flight Voucher">Flight Voucher</option>
                                        <option value="Retail" selected>Retail</option>
                                        <option value="Food & Dining">Food & Dining</option>
                                        <option value="Hotel & Travel">Hotel & Travel</option>
                                        <option value="Entertainment">Entertainment</option>
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
                                               value="2500" 
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
                                               value="25.00" 
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
                    <a href="card-details.php" class="btn btn-outline-secondary px-4 py-2 fs-6" id="btnCancel">
                        CANCEL
                    </a>
                </div>
            </form>
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
