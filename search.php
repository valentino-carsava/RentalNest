<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Properties - RentalNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="h3 fw-bold mb-3">Find Your Perfect Home</h2>
                <p class="text-muted">Over <?= $pdo->query("SELECT COUNT(*) FROM flat")->fetchColumn() ?> properties available</p>
            </div>
        </div>
        
        <div class="card filter-card mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Search Filters</h5>
            </div>
            <div class="card-body p-4">
                <form action="search_results.php" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="district" class="form-label fw-bold">District</label>
                            <select class="form-select" id="district" name="district">
                                <option value="">All Districts</option>
                                <option value="Dhaka">Dhaka</option>
                                <option value="Chattogram">Chattogram</option>
                                <option value="Sylhet">Sylhet</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="area" class="form-label fw-bold">Area</label>
                            <input type="text" class="form-control" id="area" name="area" placeholder="e.g., Badda, Gulshan">
                        </div>
                        <div class="col-md-3">
                            <label for="price_max" class="form-label fw-bold">Maximum Price (BDT)</label>
                            <input type="number" class="form-control" id="price_max" name="price_max" placeholder="e.g., 50000">
                        </div>
                        <div class="col-md-3">
                            <label for="property_type" class="form-label fw-bold">Property Type</label>
                            <select class="form-select" id="property_type" name="property_type">
                                <option value="">All Types</option>
                                <option value="single_room">Single Room</option>
                                <option value="sublet">Sublet</option>
                                <option value="full_flat">Full Flat</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-3">
                        <div class="col-md-3">
                            <label for="members_count" class="form-label fw-bold">Roommates</label>
                            <select class="form-select" id="members_count" name="members_count">
                                <option value="">Any</option>
                                <option value="1">1 (Single)</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="availability" class="form-label fw-bold">Availability</label>
                            <select class="form-select" id="availability" name="availability">
                                <option value="">All</option>
                                <option value="1">Available</option>
                                <option value="0">Not Available</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label fw-bold">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label fw-bold">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                    </div>
                    
                    <div class="mt-4 d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-search me-2"></i>Search Properties
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <h3 class="h4 fw-bold mb-3">Popular Areas</h3>
        <div class="row mb-4 g-2">
            <div class="col-md-2 col-4">
                <a href="search_results.php?area=BRAC+University+Area" class="btn btn-outline-primary w-100 py-3">
                    <i class="bi bi-geo-alt me-1"></i>BRAC University
                </a>
            </div>
            <div class="col-md-2 col-4">
                <a href="search_results.php?area=Aftabnagar" class="btn btn-outline-primary w-100 py-3">
                    <i class="bi bi-geo-alt me-1"></i>Aftabnagar
                </a>
            </div>
            <div class="col-md-2 col-4">
                <a href="search_results.php?area=Gulshan" class="btn btn-outline-primary w-100 py-3">
                    <i class="bi bi-geo-alt me-1"></i>Gulshan
                </a>
            </div>
            <div class="col-md-2 col-4">
                <a href="search_results.php?area=Dhanmondi" class="btn btn-outline-primary w-100 py-3">
                    <i class="bi bi-geo-alt me-1"></i>Dhanmondi
                </a>
            </div>
            <div class="col-md-2 col-4">
                <a href="search_results.php?area=Uttara" class="btn btn-outline-primary w-100 py-3">
                    <i class="bi bi-geo-alt me-1"></i>Uttara
                </a>
            </div>
            <div class="col-md-2 col-4">
                <a href="search_results.php?area=Banani" class="btn btn-outline-primary w-100 py-3">
                    <i class="bi bi-geo-alt me-1"></i>Banani
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                <i class="bi bi-building fs-4"></i>
                            </div>
                            <h4 class="fw-bold mb-0">Short-Term Stays</h4>
                        </div>
                        <p class="text-muted">Need a place for 2-7 days? Filter by short-term availability to find perfect temporary housing.</p>
                        <a href="search_results.php?booking_type=short_term" class="btn btn-outline-primary mt-2">
                            <i class="bi bi-calendar-range me-1"></i>Find Short-Term
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                            <h4 class="fw-bold mb-0">Student Housing</h4>
                        </div>
                        <p class="text-muted">Specialized housing near universities with student-friendly amenities and pricing.</p>
                        <a href="search_results.php?institution=BRAC+University" class="btn btn-outline-success mt-2">
                            <i class="bi bi-mortarboard me-1"></i>Find Student Housing
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 text-info rounded-circle p-2 me-3">
                                <i class="bi bi-star fs-4"></i>
                            </div>
                            <h4 class="fw-bold mb-0">Top Rated Properties</h4>
                        </div>
                        <p class="text-muted">Discover highly-rated properties with excellent reviews from previous renters.</p>
                        <a href="search_results.php?sort=rating" class="btn btn-outline-info mt-2">
                            <i class="bi bi-star me-1"></i>View Top Rated
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set min date for date pickers to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('start_date').min = today;
            
            document.getElementById('start_date').addEventListener('change', function() {
                if (this.value) {
                    document.getElementById('end_date').min = this.value;
                }
            });
        });
    </script>
</body>
</html>