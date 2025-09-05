<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'renter') {
    header('Location: login.php');
    exit();
}

// Get renter details
$stmt = $pdo->prepare("SELECT * FROM renter WHERE r_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$renter = $stmt->fetch();

// Get interested properties
$stmt = $pdo->prepare("SELECT f.*, o.name as owner_name 
                      FROM interested_properties ip
                      JOIN flat f ON ip.f_id = f.f_id
                      JOIN owner o ON f.owner_id = o.owner_id
                      WHERE ip.r_id = ?
                      ORDER BY ip.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$interested_properties = $stmt->fetchAll();

// Get upcoming bookings
$stmt = $pdo->prepare("SELECT b.*, f.area, f.district, f.street, f.price, 
                      o.name as owner_name, o.phone as owner_phone
                      FROM booking b
                      JOIN flat f ON b.owner_id = f.owner_id
                      JOIN owner o ON f.owner_id = o.owner_id
                      WHERE b.r_id = ? AND b.start_date >= CURDATE()
                      ORDER BY b.start_date ASC");
$stmt->execute([$_SESSION['user_id']]);
$upcoming_bookings = $stmt->fetchAll();

// Get past bookings
$stmt = $pdo->prepare("SELECT b.*, f.area, f.district, f.street, f.price, 
                      o.name as owner_name, o.phone as owner_phone
                      FROM booking b
                      JOIN flat f ON b.owner_id = f.owner_id
                      JOIN owner o ON f.owner_id = o.owner_id
                      WHERE b.r_id = ? AND b.end_date < CURDATE()
                      ORDER BY b.start_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$past_bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renter Dashboard - RentalNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .interest-card {
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .interest-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .remove-interest {
            color: #e53e3e;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .remove-interest:hover {
            color: #c53030;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="h2 fw-bold">Welcome back, <?= htmlspecialchars($renter['name']) ?></h1>
                <p class="text-muted">Manage your rentals, bookings, and interested properties</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="d-flex flex-column align-items-md-end">
                    <span class="badge bg-primary fs-6 px-3 py-2 mb-2">Renter Account</span>
                    <p class="text-muted mb-0"><?= htmlspecialchars($renter['st_id']) ?></p>
                    <p class="text-muted"><?= htmlspecialchars($renter['institution']) ?></p>
                </div>
            </div>
        </div>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <div class="row">
            <!-- Interested Properties Section -->
            <div class="col-md-6 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h4 fw-bold">Your Interested Properties</h2>
                    <span class="badge bg-primary fs-6 px-3 py-2"><?= count($interested_properties) ?></span>
                </div>
                
                <?php if (count($interested_properties) > 0): ?>
                    <?php foreach ($interested_properties as $property): ?>
                        <div class="card interest-card mb-3 border-0 shadow-sm">
                            <div class="row g-0">
                                <div class="col-4">
                                    <div class="position-relative h-100" style="background: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80') center/cover; height: 120px; border-radius: 8px 0 0 8px;"></div>
                                </div>
                                <div class="col-8">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title h6 fw-bold mb-1"><?= htmlspecialchars($property['area']) ?></h5>
                                            <div class="remove-interest" data-f_id="<?= $property['f_id'] ?>">
                                                <i class="bi bi-x-circle fs-5"></i>
                                            </div>
                                        </div>
                                        <p class="card-text text-muted small mb-2">
                                            <?= htmlspecialchars($property['district']) ?>, <?= htmlspecialchars($property['street']) ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="text-primary fw-bold mb-0">৳<?= number_format($property['price']) ?></h6>
                                            <a href="property_details.php?f_id=<?= $property['f_id'] ?>" class="btn btn-sm btn-outline-primary">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card border-0 shadow-sm p-4 text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-heart fs-2 text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-3">No Interested Properties</h4>
                        <p class="text-muted mb-4">Find properties you like and mark them as interested to save them for later.</p>
                        <a href="search.php" class="btn btn-primary px-4 py-2">
                            <i class="bi bi-search me-2"></i>Find Properties
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Upcoming Bookings Section -->
            <div class="col-md-6 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h4 fw-bold">Upcoming Bookings</h2>
                    <span class="badge bg-success fs-6 px-3 py-2"><?= count($upcoming_bookings) ?></span>
                </div>
                
                <?php if (count($upcoming_bookings) > 0): ?>
                    <?php foreach ($upcoming_bookings as $booking): ?>
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title h5 fw-bold mb-0"><?= htmlspecialchars($booking['area']) ?></h5>
                                    <span class="badge bg-success fs-6 px-3 py-2">Upcoming</span>
                                </div>
                                <p class="card-text text-muted small mb-3">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    <?= htmlspecialchars($booking['district']) ?>, <?= htmlspecialchars($booking['street']) ?>
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-1">Dates</h6>
                                        <p class="text-muted mb-0">
                                            <?= date('M d, Y', strtotime($booking['start_date'])) ?> - 
                                            <?= date('M d, Y', strtotime($booking['end_date'])) ?>
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <h6 class="fw-bold mb-1">Total</h6>
                                        <p class="text-primary fw-bold mb-0">৳<?= number_format($booking['price']) ?></p>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                    <div>
                                        <h6 class="fw-bold mb-1">Owner</h6>
                                        <p class="text-muted mb-0"><?= htmlspecialchars($booking['owner_name']) ?></p>
                                    </div>
                                    <a href="property_details.php?f_id=<?= $booking['f_id'] ?>" class="btn btn-outline-primary btn-sm">
                                        View Property
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card border-0 shadow-sm p-4 text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-calendar-check fs-2 text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-3">No Upcoming Bookings</h4>
                        <p class="text-muted mb-4">You don't have any upcoming bookings. Book a property to see it here.</p>
                        <a href="search.php" class="btn btn-primary px-4 py-2">
                            <i class="bi bi-search me-2"></i>Find Properties
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Past Bookings Section -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h4 fw-bold">Past Bookings</h2>
                    <span class="badge bg-secondary fs-6 px-3 py-2"><?= count($past_bookings) ?></span>
                </div>
                
                <?php if (count($past_bookings) > 0): ?>
                    <div class="row">
                        <?php foreach ($past_bookings as $index => $booking): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title h5 fw-bold mb-0"><?= htmlspecialchars($booking['area']) ?></h5>
                                            <span class="badge bg-secondary fs-6 px-3 py-2">Completed</span>
                                        </div>
                                        <p class="card-text text-muted small mb-3">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            <?= htmlspecialchars($booking['district']) ?>, <?= htmlspecialchars($booking['street']) ?>
                                        </p>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h6 class="fw-bold mb-1">Dates</h6>
                                                <p class="text-muted mb-0">
                                                    <?= date('M d, Y', strtotime($booking['start_date'])) ?> - 
                                                    <?= date('M d, Y', strtotime($booking['end_date'])) ?>
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <h6 class="fw-bold mb-1">Total</h6>
                                                <p class="text-primary fw-bold mb-0">৳<?= number_format($booking['price']) ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <div>
                                                <h6 class="fw-bold mb-1">Owner</h6>
                                                <p class="text-muted mb-0"><?= htmlspecialchars($booking['owner_name']) ?></p>
                                            </div>
                                            <a href="property_details.php?f_id=<?= $booking['f_id'] ?>" class="btn btn-outline-primary btn-sm">
                                                View Property
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="card border-0 shadow-sm p-4 text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-clock-history fs-2 text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-3">No Past Bookings</h4>
                        <p class="text-muted mb-4">Your past bookings will appear here after your stay is completed.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Remove interest functionality
            document.querySelectorAll('.remove-interest').forEach(button => {
                button.addEventListener('click', function() {
                    const f_id = this.getAttribute('data-f_id');
                    const card = this.closest('.card');
                    
                    if (confirm('Are you sure you want to remove this property from your interested list?')) {
                        fetch('interest_process.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'f_id=' + f_id
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.action === 'removed') {
                                // Remove the card
                                card.parentElement.removeChild(card);
                                
                                // Update count
                                const countBadge = document.querySelector('.col-md-6:first-child .badge');
                                let count = parseInt(countBadge.textContent);
                                countBadge.textContent = Math.max(0, count - 1);
                                
                                // Show success message
                                const alertDiv = document.createElement('div');
                                alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                                alertDiv.role = 'alert';
                                alertDiv.innerHTML = data.message + ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                                document.querySelector('.col-md-6:first-child').appendChild(alertDiv);
                            } else {
                                // Show error message
                                const alertDiv = document.createElement('div');
                                alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                                alertDiv.role = 'alert';
                                alertDiv.innerHTML = data.message + ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                                document.querySelector('.col-md-6:first-child').appendChild(alertDiv);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                            alertDiv.role = 'alert';
                            alertDiv.innerHTML = 'Error removing from interested list. Please try again. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                            document.querySelector('.col-md-6:first-child').appendChild(alertDiv);
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>