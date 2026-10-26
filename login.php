<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - AirAsia Rewards</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@latest/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <!-- Top Navigation Bar with Left-Side Framing -->
    <header class="top-navbar">
        <div class="container">
            <nav class="navbar navbar-expand-lg p-0">
                <a href="login.php" class="navbar-brand-framing" aria-label="AirAsia Home">
                    <img src="images/airasia_nobackground.jpg" alt="AirAsia Logo" class="brand-logo">
                    <div class="brand-text">
                        <span class="brand-subtitle">Rewards Program</span>
                    </div>
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content Area: General Wireframe Structure -->
    <main class="container my-auto py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-7 col-xl-6">
                <!-- Outer Frame Wrapper (matching the wireframe outer container structure) -->
                <div class="outer-frame">
                    <!-- Bootstrap Card -->
                    <div class="card card-reactive shadow-sm">
                        <div class="card-body p-4 p-sm-5">
                            <h1 class="card-title-header">Sign-In</h1>

                            <!-- Login Form -->
                            <form action="card-list.php" method="POST" id="loginForm">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control" 
                                               id="username" 
                                               name="username" 
                                               placeholder="Enter your username" 
                                               required 
                                               autocomplete="username">
                                        <div class="invalid-feedback">
                                            Please provide your username.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Enter your password" 
                                               required 
                                               autocomplete="current-password">
                                        <div class="invalid-feedback">
                                            Please provide your password.
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 mb-4">
                                    <button type="submit" class="btn btn-airasia" id="btnLogin">LOG IN</button>
                                </div>

                                <div class="text-center">
                                    <a href="#" class="forgot-password-link" id="linkForgotPassword">Forgot Password?</a>
                                </div>
                            </form>
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