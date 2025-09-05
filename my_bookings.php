<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Determine if user is a renter or owner
$is_renter = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'renter';
$is_owner = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'owner';

// Get bookings based on user type
if ($is_renter) {
    $stmt = $pdo->prepare("SELECT b.*, f.area, f.district, f.street, f.price, f.availability, f.f_id,
                          o.name as owner_name, o.phone as owner_phone
                          FROM booking b
                          JOIN flat f ON b.owner_id = f.owner_id
                          JOIN owner o ON f.owner_id = o.owner_id
                          WHERE b.r_id = ?
                          ORDER BY b.start_date DESC");
    $stmt->execute([$_SESSION['user_id']]);
} else if ($is_owner) {
    $stmt = $pdo->prepare("SELECT b.*, f.area, f.district, f.street, f.price, f.availability, f.f_id,
                          r.name as renter_name, r.phone as renter_phone, r.r_id
                          FROM booking b
                          JOIN flat f ON b.owner_id = f.owner_id
                          JOIN renter r ON b.r_id = r.r_id
                          WHERE b.owner_id = ?
                          ORDER BY b.start_date DESC");
    $stmt->execute([$_SESSION['user_id']]);
} else {
    header('Location: login.php');
    exit();
}

$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - RentalNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="h3 fw-bold">My Bookings</h2>
                <p class="text-muted">
                    <?php if ($is_renter): ?>
                        View and manage your property bookings
                    <?php else: ?>
                        View and manage your property bookings from renters
                    <?php endif; ?>
                </p>
            </div>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if (count($bookings) > 0): ?>
            <div class="row">
                <?php foreach ($bookings as $booking): 
                    $currentDate = new DateTime();
                    $startDate = new DateTime($booking['start_date']);
                    $endDate = new DateTime($booking['end_date']);
                    
                    if ($currentDate < $startDate) {
                        $status = 'upcoming';
                        $statusText = 'Upcoming';
                        $statusClass = 'primary';
                    } elseif ($currentDate >= $startDate && $currentDate <= $endDate) {
                        $status = 'current';
                        $statusText = 'Current';
                        $statusClass = 'success';
                    } else {
                        $status = 'completed';
                        $statusText = 'Completed';
                        $statusClass = 'secondary';
                    }
                ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 property-card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title h5 fw-bold mb-0"><?= htmlspecialchars($booking['area']) ?></h5>
                                    <span class="badge bg-<?= $statusClass ?> fs-6 px-3 py-2"><?= $statusText ?></span>
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
                                
                                <?php if ($is_renter): ?>
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                        <div>
                                            <h6 class="fw-bold mb-1">Owner</h6>
                                            <p class="text-muted mb-0"><?= htmlspecialchars($booking['owner_name']) ?></p>
                                        </div>
                                        <div class="d-flex flex-column gap-2">
                                            <a href="property_details.php?f_id=<?= $booking['f_id'] ?>" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye me-1"></i>View
                                            </a>
                                            <?php if ($status === 'upcoming'): ?>
                                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                                        data-bs-toggle="modal" data-bs-target="#cancelModal-<?= $booking['b_id'] ?>">
                                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                                </button>
                                                
                                                <!-- Cancel Booking Modal -->
                                                <div class="modal fade" id="cancelModal-<?= $booking['b_id'] ?>" tabindex="-1" 
                                                     aria-labelledby="cancelModalLabel-<?= $booking['b_id'] ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="cancelModalLabel-<?= $booking['b_id'] ?>">
                                                                    Cancel Booking
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to cancel your booking for <strong><?= htmlspecialchars($booking['area']) ?></strong>?</p>
                                                                <p class="text-danger">
                                                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                                                    This action cannot be undone.
                                                                </p>
                                                                <div class="alert alert-warning mt-3">
                                                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                                                    Note: Due to system limitations, the property availability status might not update immediately.
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    <i class="bi bi-arrow-left me-2"></i>Go Back
                                                                </button>
                                                                <a href="cancel_booking.php?b_id=<?= $booking['b_id'] ?>&f_id=<?= $booking['f_id'] ?>" 
                                                                   class="btn btn-danger">
                                                                    <i class="bi bi-x-circle me-2"></i>Cancel Booking
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                        <div>
                                            <h6 class="fw-bold mb-1">Renter</h6>
                                            <p class="text-muted mb-0"><?= htmlspecialchars($booking['renter_name']) ?></p>
                                        </div>
                                        <a href="renter_dashboard.php?r_id=<?= $booking['r_id'] ?>" class="btn btn-outline-primary btn-sm">
                                            View Profile
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-calendar-check fs-2 text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-3">No Bookings Found</h4>
                    <p class="text-muted mb-4">You don't have any bookings yet. Start by searching for properties that match your needs.</p>
                    <a href="search.php" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-search me-2"></i>Find Properties
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>