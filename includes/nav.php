    <!-- Top Navigation Bar with Left-Side Framing & Right-Side Actions -->
    <header class="top-navbar">
        <div class="container">
            <nav class="navbar navbar-expand-lg p-0">
                <a href="index.php" class="navbar-brand-framing" aria-label="AirAsia Home">
                    <img src="images/airasia_nobackground.jpg" alt="AirAsia Logo" class="brand-logo">
                    <div class="brand-text">
                        <span class="brand-subtitle">Rewards Program</span>
                    </div>
                </a>

                <!-- Nav Actions matching wireframe (Welcome message, Home, Gift Cards List, Gift Cards Add, Add a Customer, Log Out) -->
                <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 gap-md-3 ms-auto mt-2 mt-md-0">
                    <span class="nav-user-greeting d-none d-sm-inline-flex">
                        <i class="bi bi-person-circle"></i>
                        <span>Welcome, <span class="nav-user-name">Member</span></span>
                    </span>
                    <a href="index.php" class="btn-nav-action" id="navHome" title="Home page">
                        <i class="bi bi-house-door"></i>
                        <span>HOME</span>
                    </a>
                    <a href="card-add.php" class="btn-nav-action btn-nav-admin" id="navAddCard" title="Gift cards add">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>GIFT CARDS ADD</span>
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

