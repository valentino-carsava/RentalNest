<?php
session_start();
include 'config.php';

// Check if user is logged in and is an owner
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'owner') {
    header('Location: login.php');
    exit();
}

// Get owner details
$stmt = $pdo->prepare("SELECT * FROM owner WHERE owner_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$owner = $stmt->fetch();

// Get owner's properties
$stmt = $pdo->prepare("SELECT * FROM flat WHERE owner_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$properties = $stmt->fetchAll();

// Get booking statistics
$bookingStats = [
    'total' => 0,
    'upcoming' => 0,
    'current' => 0,
    'completed' => 0
];

if (count($properties) > 0) {
    $propertyIds = array_column($properties, 'f_id');
    $placeholders = implode(',', array_fill(0, count($propertyIds), '?'));
    
    $stmt = $pdo->prepare("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN start_date > CURDATE() THEN 1 ELSE 0 END) as upcoming,
        SUM(CASE WHEN start_date <= CURDATE() AND end_date >= CURDATE() THEN 1 ELSE 0 END) as current,
        SUM(CASE WHEN end_date < CURDATE() THEN 1 ELSE 0 END) as completed
        FROM booking WHERE owner_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $stats = $stmt->fetch();
    
    $bookingStats = [
        'total' => $stats['total'],
        'upcoming' => $stats['upcoming'],
        'current' => $stats['current'],
        'completed' => $stats['completed']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RentalNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container py-5">
        <div class="row">
            <div class="col-md-3">
                <div class="card dashboard-sidebar">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link active" href="owner_dashboard.php">
                                    <i class="bi bi-house me-2"></i>My Properties
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="add_property.php">
                                    <i class="bi bi-plus-circle me-2"></i>Add Property
                                </a>
                            </li>
                            <?php if ($_SESSION['owner_type'] === 'Flat'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-cone-striped me-2"></i>Manage Services
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link" href="my_bookings.php">
                                    <i class="bi bi-calendar-check me-2"></i>My Bookings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-graph-up me-2"></i>Earnings
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold">My Properties</h2>
                    <a href="add_property.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Property
                    </a>
                </div>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-muted mb-1">Total Properties</h6>
                                        <h3 class="mb-0 fw-bold"><?= count($properties) ?></h3>
                                    </div>
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                                        <i class="bi bi-house fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-muted mb-1">Total Bookings</h6>
                                        <h3 class="mb-0 fw-bold"><?= $bookingStats['total'] ?></h3>
                                    </div>
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                                        <i class="bi bi-calendar-check fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-muted mb-1">Upcoming</h6>
                                        <h3 class="mb-0 fw-bold"><?= $bookingStats['upcoming'] ?></h3>
                                    </div>
                                    <div class="bg-info bg-opacity-10 text-info rounded-circle p-3">
                                        <i class="bi bi-calendar-plus fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-muted mb-1">Occupied</h6>
                                        <h3 class="mb-0 fw-bold"><?= $bookingStats['current'] ?></h3>
                                    </div>
                                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (count($properties) > 0): ?>
                    <div class="row">
                        <?php foreach ($properties as $property): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 property-card border-0 shadow-sm">
                                    <div class="property-image" style="background-image: url('https://images.unsplash.com/photo-1493692165395-d390a0d815ec?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80');">
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-<?= $property['availability'] ? 'success' : 'secondary' ?>">
                                                <?= $property['availability'] ? 'Available' : 'Not Available' ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title h5 fw-bold"><?= htmlspecialchars($property['area']) ?></h5>
                                        <p class="card-text text-muted small">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            <?= htmlspecialchars($property['district']) ?>, <?= htmlspecialchars($property['street']) ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <h4 class="text-primary fw-bold mb-0">৳<?= number_format($property['price']) ?></h4>
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-people me-1"></i>
                                                <?= htmlspecialchars($property['members_count']) ?> Roommates
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top-0">
                                        <div class="d-flex justify-content-between">
                                            <a href="property_details.php?f_id=<?= $property['f_id'] ?>" class="btn btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i>View
                                            </a>
                                            <a href="edit_property.php?f_id=<?= $property['f_id'] ?>" class="btn btn-outline-secondary">
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        You haven't added any properties yet. <a href="add_property.php" class="alert-link">Add your first property</a> to start earning.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    
                    <div class="text-center py-5 bg-light rounded-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-4 d-inline-block mb-4">
                            <i class="bi bi-house-add fs-1"></i>
                        </div>
                        <h4 class="mb-3 fw-bold">No Properties Listed Yet</h4>
                        <p class="text-muted mb-4">List your first property and start connecting with potential renters today.</p>
                        <a href="add_property.php" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-plus-circle me-2"></i>Add Your First Property
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>