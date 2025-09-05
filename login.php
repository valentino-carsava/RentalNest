<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RentalNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white py-4">
                        <h3 class="text-center mb-0">Welcome to RentalNest</h3>
                        <p class="text-center mb-0">Sign in to your account</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $_SESSION['error'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        
                        <!-- Login Tabs -->
                        <ul class="nav nav-tabs mb-4" id="loginTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="renter-tab" data-bs-toggle="tab" data-bs-target="#renter" type="button" role="tab" aria-controls="renter" aria-selected="true">
                                    <i class="bi bi-person me-2"></i>Renter Login
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="owner-tab" data-bs-toggle="tab" data-bs-target="#owner" type="button" role="tab" aria-controls="owner" aria-selected="false">
                                    <i class="bi bi-house me-2"></i>Owner Login
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="loginTabContent">
                            <!-- Renter Login Form -->
                            <div class="tab-pane fade show active" id="renter" role="tabpanel" aria-labelledby="renter-tab">
                                <form action="login_process.php" method="POST">
                                    <input type="hidden" name="user_type" value="renter">
                                    
                                    <div class="mb-4">
                                        <label for="renter_id" class="form-label">Student ID</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="bi bi-people"></i>
                                            </span>
                                            <input type="text" class="form-control form-control-lg" id="renter_id" name="renter_id" required 
                                                   placeholder="Enter your student ID (e.g., 23201186)">
                                        </div>
                                        <div class="form-text">Example: 23201186 (Rakibul Hasan)</div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="renter_password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="bi bi-lock"></i>
                                            </span>
                                            <input type="password" class="form-control form-control-lg" id="renter_password" name="password" required 
                                                   placeholder="Enter your password" value="password123">
                                        </div>
                                        <div class="form-text">Demo password for all accounts: password123</div>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg py-3">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In as Renter
                                        </button>
                                    </div>
                                </form>
                                
                                <div class="mt-4 text-center">
                                    <p class="demo-info mb-2">Demo Renter Accounts:</p>
                                    <ul class="list-unstyled">
                                        <li class="mb-1"><strong>Student ID:</strong> 23201186 | <strong>Name:</strong> Rakibul Hasan</li>
                                        <li class="mb-1"><strong>Student ID:</strong> 2501026 | <strong>Name:</strong> Shamima Akter</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Owner Login Form -->
                            <div class="tab-pane fade" id="owner" role="tabpanel" aria-labelledby="owner-tab">
                                <form action="login_process.php" method="POST">
                                    <input type="hidden" name="user_type" value="owner">
                                    
                                    <div class="mb-4">
                                        <label for="owner_email" class="form-label">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control form-control-lg" id="owner_email" name="email" required 
                                                   placeholder="Enter your email address" value="arif.hossain@example.com">
                                        </div>
                                        <div class="form-text">Example: arif.hossain@example.com (Arif Hossain)</div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="owner_password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="bi bi-lock"></i>
                                            </span>
                                            <input type="password" class="form-control form-control-lg" id="owner_password" name="password" required 
                                                   placeholder="Enter your password" value="password123">
                                        </div>
                                        <div class="form-text">Demo password for all accounts: password123</div>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg py-3">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In as Owner
                                        </button>
                                    </div>
                                </form>
                                
                                <div class="mt-4 text-center">
                                    <p class="demo-info mb-2">Demo Owner Accounts:</p>
                                    <ul class="list-unstyled">
                                        <li class="mb-1"><strong>Email:</strong> arif.hossain@example.com | <strong>Name:</strong> Arif Hossain</li>
                                        <li class="mb-1"><strong>Email:</strong> nabila.sultana@example.com | <strong>Name:</strong> Nabila Sultana</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>