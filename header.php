<?php
// Only start session if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentalNest - Find Your Perfect Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="favicon.png">
    <style>
        /* Custom header styles */
        .navbar-custom {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 0.75rem 0;
            transition: all 0.3s ease;
            height: 70px;
        }
        
        .navbar-custom.scrolled {
            background: linear-gradient(135deg, #3a56d4 0%, #2a087d 100%);
            padding: 0.5rem 0;
            height: 60px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .logo-icon {
            background: linear-gradient(135deg, #f8f9fa 0%, #e2e8f0 100%);
            border-radius: 12px;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .logo-icon i {
            font-size: 1.2rem;
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .logo-text {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .logo-text span {
            background: linear-gradient(to right, #4cc9f0, #7209b7);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .nav-link:hover, .nav-link.active {
            color: white !important;
            background: rgba(255, 255, 255, 0.1) !important;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: white;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after, .nav-link.active::after {
            width: 70%;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4cc9f0, #7209b7);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.85rem;
            margin-right: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4cc9f0, #7209b7) !important;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(114, 9, 183, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(114, 9, 183, 0.4);
            background: linear-gradient(135deg, #4361ee, #560bad) !important;
        }
        
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
        }
        
        @media (max-width: 992px) {
            .mobile-menu-toggle {
                display: block;
            }
            
            .desktop-menu {
                display: none;
            }
            
            .logo-text {
                font-size: 1.3rem;
            }
            
            .logo-icon {
                padding: 6px;
            }
        }
        
        @media (min-width: 992px) {
            .mobile-menu {
                display: none;
            }
        }
        
        /* Animation for the logo */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }
        
        .logo-icon {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Dropdown menu styling */
        .dropdown-menu {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: none;
            margin-top: 8px;
            padding: 0.5rem 0;
        }
        
        .dropdown-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            color: white !important;
        }
        
        .dropdown-item i {
            width: 20px;
            text-align: center;
        }
        
        /* User menu styling */
        .user-menu {
            min-width: 220px;
        }
        
        .user-header {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-info {
            flex: 1;
        }
        
        .user-name {
            font-weight: 600;
            color: white;
            margin-bottom: 0;
            font-size: 1.1rem;
        }
        
        .user-role {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 0.75rem;
            display: inline-block;
        }
    </style>
</head>
<body>
    <header class="navbar-custom fixed-top">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Logo -->
                <a href="index.php" class="logo">
                    <div class="logo-icon">
                        <i class="bi bi-house-fill"></i>
                    </div>
                    <div class="logo-text">Rental<span>Nest</span></div>
                </a>
                
                <!-- Desktop Menu -->
                <nav class="desktop-menu">
                    <ul class="nav d-flex align-items-center">
                        <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                        <li class="nav-item"><a href="search.php" class="nav-link">Search</a></li>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['user_type'] === 'renter'): ?>
                                <li class="nav-item"><a href="renter_dashboard.php" class="nav-link">Dashboard</a></li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar">
                                                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                                            </div>
                                            <span><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu user-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        <li class="user-header">
                                            <div class="user-avatar" style="width: 40px; height: 40px; font-size: 1.1rem;">
                                                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                                            </div>
                                            <div class="user-info">
                                                <h6 class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></h6>
                                                <span class="user-role">Renter</span>
                                            </div>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="renter_dashboard.php"><i class="bi bi-person"></i> My Profile</a></li>
                                        <li><a class="dropdown-item" href="my_bookings.php"><i class="bi bi-calendar-check"></i> My Bookings</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-star"></i> My Reviews</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li class="nav-item"><a href="owner_dashboard.php" class="nav-link">Dashboard</a></li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="ownerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar">
                                                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                                            </div>
                                            <span><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu user-menu dropdown-menu-end" aria-labelledby="ownerDropdown">
                                        <li class="user-header">
                                            <div class="user-avatar" style="width: 40px; height: 40px; font-size: 1.1rem;">
                                                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                                            </div>
                                            <div class="user-info">
                                                <h6 class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></h6>
                                                <span class="user-role"><?= $_SESSION['owner_type'] ?? 'Owner' ?></span>
                                            </div>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="owner_dashboard.php"><i class="bi bi-house"></i> My Properties</a></li>
                                        <li><a class="dropdown-item" href="add_property.php"><i class="bi bi-plus-circle"></i> Add Property</a></li>
                                        <li><a class="dropdown-item" href="my_bookings.php"><i class="bi bi-calendar-check"></i> My Bookings</a></li>
                                        <?php if ($_SESSION['owner_type'] === 'Flat'): ?>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-cone-striped"></i> Manage Services</a></li>
                                        <?php endif; ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
                            <li class="nav-item"><a href="signup.php" class="nav-link">Sign Up</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                    <i class="bi bi-list"></i>
                </button>
                
                <!-- Mobile Menu (Offcanvas) -->
                <div class="offcanvas offcanvas-end bg-primary text-white" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="nav flex-column">
                            <li class="nav-item"><a href="index.php" class="nav-link text-white">Home</a></li>
                            <li class="nav-item"><a href="search.php" class="nav-link text-white">Search</a></li>
                            
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php if ($_SESSION['user_type'] === 'renter'): ?>
                                    <li class="nav-item"><a href="renter_dashboard.php" class="nav-link text-white">Dashboard</a></li>
                                    <li class="nav-item"><a href="my_bookings.php" class="nav-link text-white">My Bookings</a></li>
                                <?php else: ?>
                                    <li class="nav-item"><a href="owner_dashboard.php" class="nav-link text-white">Dashboard</a></li>
                                    <li class="nav-item"><a href="add_property.php" class="nav-link text-white">Add Property</a></li>
                                    <li class="nav-item"><a href="my_bookings.php" class="nav-link text-white">My Bookings</a></li>
                                <?php endif; ?>
                                <li class="nav-item"><a href="logout.php" class="nav-link text-white">Logout</a></li>
                            <?php else: ?>
                                <li class="nav-item"><a href="login.php" class="nav-link text-white">Login</a></li>
                                <li class="nav-item"><a href="signup.php" class="nav-link text-white">Sign Up</a></li>
                            <?php endif; ?>
                        </ul>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="mt-4 p-3 bg-white bg-opacity-10 rounded-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="user-avatar me-3">
                                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></h6>
                                        <small class="text-white-50">
                                            <?= $_SESSION['user_type'] === 'renter' ? 'Renter' : ($_SESSION['owner_type'] ?? 'Owner') ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <script>
        // Header scroll effect
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('.navbar-custom');
            let lastScroll = 0;
            
            window.addEventListener('scroll', function() {
                const currentScroll = window.pageYOffset;
                
                // Add shadow when scrolling
                if (currentScroll > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
                
                lastScroll = currentScroll;
            });
            
            // Close mobile menu when clicking a link
            document.querySelectorAll('#mobileMenu .nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('mobileMenu'));
                    if (offcanvas) {
                        offcanvas.hide();
                    }
                });
            });
        });
    </script>