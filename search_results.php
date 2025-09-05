<?php
session_start();
include 'config.php';

// Get search parameters
$district = $_GET['district'] ?? '';
$area = $_GET['area'] ?? '';
$price_max = $_GET['price_max'] ?? '';
$property_type = $_GET['property_type'] ?? '';
$members_count = $_GET['members_count'] ?? '';
$availability = isset($_GET['availability']) ? $_GET['availability'] : '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Build the query
$sql = "SELECT f.* FROM flat f WHERE 1=1";
$params = [];

if (!empty($district)) {
    $sql .= " AND f.district = ?";
    $params[] = $district;
}

if (!empty($area)) {
    $sql .= " AND f.area LIKE ?";
    $params[] = "%" . $area . "%";
}

if (!empty($price_max)) {
    $sql .= " AND f.price <= ?";
    $params[] = $price_max;
}

if (!empty($members_count)) {
    $sql .= " AND f.members_count = ?";
    $params[] = $members_count;
}

if ($availability !== '') {
    $sql .= " AND f.availability = ?";
    $params[] = $availability;
}

// Execute the query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$properties = $stmt->fetchAll();

// Calculate search statistics
$total_properties = count($properties);
$available_properties = 0;
foreach ($properties as $property) {
    if ($property['availability']) $available_properties++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - RentalNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="h3 fw-bold mb-1">Search Results</h2>
                <p class="text-muted">
                    <?php if ($total_properties > 0): ?>
                        Found <span class="fw-bold"><?= $total_properties ?></span> properties matching your criteria.
                        <?php if ($available_properties < $total_properties): ?>
                            <span class="ms-2 text-success">(<?= $available_properties ?> available now)</span>
                        <?php endif; ?>
                    <?php else: ?>
                        No properties found matching your criteria.
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="search.php" class="btn btn-outline-primary">
                    <i class="bi bi-funnel me-1"></i>Modify Search
                </a>
            </div>
        </div>
        
        <?php if ($total_properties > 0): ?>
            <div class="row">
                <?php foreach ($properties as $property): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 property-card border-0 shadow-sm">
                            <div class="property-image" style="background-image: url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80');">
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-<?= $property['availability'] ? 'success' : 'secondary' ?>">
                                        <?= $property['availability'] ? 'Available' : 'Not Available' ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title h5 fw-bold mb-0"><?= htmlspecialchars($property['area']) ?></h5>
                                    <div class="text-warning">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-2">
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
                                <a href="property_details.php?f_id=<?= $property['f_id'] ?>" class="btn btn-primary w-100">
                                    <i class="bi bi-eye me-2"></i>View Details
                                </a>
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
                        <i class="bi bi-search fs-1 text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-3">No Properties Found</h4>
                    <p class="text-muted mb-4">Try adjusting your search filters or check back later as new properties are added daily.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="search.php" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Filters
                        </a>
                        <a href="#" class="btn btn-primary">
                            <i class="bi bi-bell me-2"></i>Create Alert
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>