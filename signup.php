<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - RentalNest</title>
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
                        <h3 class="text-center mb-0">Create Your Account</h3>
                        <p class="text-center mb-0">Join RentalNest to find or list properties</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $_SESSION['error'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        
                        <?php if(isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $_SESSION['success'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        
                        <!-- Signup Tabs -->
                        <ul class="nav nav-tabs mb-4" id="signupTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="renter-tab" data-bs-toggle="tab" data-bs-target="#renter" type="button" role="tab" aria-controls="renter" aria-selected="true">
                                    <i class="bi bi-person me-2"></i>I'm a Renter
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="owner-tab" data-bs-toggle="tab" data-bs-target="#owner" type="button" role="tab" aria-controls="owner" aria-selected="false">
                                    <i class="bi bi-house me-2"></i>I'm a Property Owner
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="signupTabContent">
                            <!-- Renter Signup Form -->
                            <div class="tab-pane fade show active" id="renter" role="tabpanel" aria-labelledby="renter-tab">
                                <form action="signup_process.php" method="POST">
                                    <input type="hidden" name="user_type" value="renter">
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="renter_name" class="form-label">Full Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white">
                                                        <i class="bi bi-person"></i>
                                                    </span>
                                                    <input type="text" class="form-control" id="renter_name" name="name" required 
                                                           placeholder="Enter your full name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="renter_student_id" class="form-label">Student ID</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white">
                                                        <i class="bi bi-people"></i>
                                                    </span>
                                                    <input type="text" class="form-control" id="renter_student_id" name="st_id" required 
                                                           placeholder="Enter your student ID">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="renter_phone" class="form-label">Phone Number</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white">
                                                        <i class="bi bi-telephone"></i>
                                                    </span>
                                                    <input type="tel" class="form-control" id="renter_phone" name="phone" required 
                                                           placeholder="Enter your phone number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="renter_gender" class="form-label">Gender</label>
                                                <select class="form-select" id="renter_gender" name="gender" required>
                                                    <option value="">Select Gender</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="renter_institution" class="form-label">Institution</label>
                                                <input type="text" class="form-control" id="renter_institution" name="institution" required 
                                                       placeholder="Enter your institution name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="renter_room_preference" class="form-label">Room Preference</label>
                                                <select class="form-select" id="renter_room_preference" name="room_preference" required>
                                                    <option value="">Select Preference</option>
                                                    <option value="Single Room">Single Room</option>
                                                    <option value="Shared Room">Shared Room</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="renter_address" class="form-label">Address</label>
                                        <textarea class="form-control" id="renter_address" name="address" rows="2" 
                                                  placeholder="Enter your current address"></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="renter_password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="bi bi-lock"></i>
                                            </span>
                                            <input type="password" class="form-control" id="renter_password" name="password" required 
                                                   placeholder="Create a password">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="renter_password_confirm" class="form-label">Confirm Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="bi bi-lock"></i>
                                            </span>
                                            <input type="password" class="form-control" id="renter_password_confirm" name="password_confirm" required 
                                                   placeholder="Confirm your password">
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg py-3">
                                            <i class="bi bi-person-plus me-2"></i>Create Renter Account
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Owner Signup Form -->
                            <div class="tab-pane fade" id="owner" role="tabpanel" aria-labelledby="owner-tab">
                                <form action="signup_process.php" method="POST">
                                    <input type="hidden" name="user_type" value="owner">
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="owner_name" class="form-label">Full Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white">
                                                        <i class="bi bi-person"></i>
                                                    </span>
                                                    <input type="text" class="form-control" id="owner_name" name="name" required 
                                                           placeholder="Enter your full name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="owner_email" class="form-label">Email Address</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white">
                                                        <i class="bi bi-envelope"></i>
                                                    </span>
                                                    <input type="email" class="form-control" id="owner_email" name="email" required 
                                                           placeholder="Enter your email address">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="owner_phone" class="form-label">Phone Number</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white">
                                                        <i class="bi bi-telephone"></i>
                                                    </span>
                                                    <input type="tel" class="form-control" id="owner_phone" name="phone" required 
                                                           placeholder="Enter your phone number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="owner_nid" class="form-label">NID Number</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white">
                                                        <i class="bi bi-credit-card-2-front"></i>
                                                    </span>
                                                    <input type="text" class="form-control" id="owner_nid" name="nid" required 
                                                           placeholder="Enter your NID number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="owner_type" class="form-label">Owner Type</label>
                                                <select class="form-select" id="owner_type" name="owner_type" required>
                                                    <option value="">Select Type</option>
                                                    <option value="Flat">Flat Owner</option>
                                                    <option value="Sublet">Sublet Owner</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="owner_gender" class="form-label">Gender</label>
                                                <select class="form-select" id="owner_gender" name="gender" required>
                                                    <option value="">Select Gender</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="owner_address" class="form-label">Address</label>
                                        <textarea class="form-control" id="owner_address" name="address" rows="2" 
                                                  placeholder="Enter your current address"></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="owner_password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="bi bi-lock"></i>
                                            </span>
                                            <input type="password" class="form-control" id="owner_password" name="password" required 
                                                   placeholder="Create a password">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="owner_password_confirm" class="form-label">Confirm Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="bi bi-lock"></i>
                                            </span>
                                            <input type="password" class="form-control" id="owner_password_confirm" name="password_confirm" required 
                                                   placeholder="Confirm your password">
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg py-3">
                                            <i class="bi bi-house-add me-2"></i>Create Owner Account
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <p class="text-dark">Already have an account? <a href="login.php" class="text-primary">Sign in</a></p>
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