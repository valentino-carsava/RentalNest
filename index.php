<?php
session_start();
include 'config.php';

// Get featured properties (available ones)
$stmt = $pdo->prepare("SELECT * FROM flat WHERE availability = 1 ORDER BY date_posted DESC LIMIT 6");
$stmt->execute();
$featured_properties = $stmt->fetchAll();
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
</head>
<body>
    <?php include 'header.php'; ?>
    
    <main>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="display-4 mb-4">Find Your Perfect Home</h1>
                        <p class="lead mb-4">Rent, sublet, or list properties with confidence. RentalNest connects you with the best housing options in Bangladesh.</p>
                        <a href="search.php" class="btn btn-light btn-lg shadow-sm">Find Your Room</a>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative">
                            <div class="position-absolute top-0 start-0 w-100 h-100" 
                                 style="background: linear-gradient(135deg, rgba(30, 58, 138, 0.7) 0%, rgba(29, 78, 216, 0.7) 100%); border-radius: 20px;"></div>
                            <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                 class="img-fluid rounded-4 shadow-lg" 
                                 alt="Modern apartment interior" 
                                 style="z-index: 1; position: relative;">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Search Section -->
        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card filter-card">
                            <div class="card-body p-4">
                                <form action="search_results.php" method="GET">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white">
                                                    <i class="bi bi-geo-alt"></i>
                                                </span>
                                                <input type="text" class="form-control" name="area" placeholder="Location (e.g., Badda, Gulshan)">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white">
                                                    <i class="bi bi-building"></i>
                                                </span>
                                                <select class="form-select" name="district">
                                                    <option value="">All Districts</option>
                                                    <option value="Dhaka">Dhaka</option>
                                                    <option value="Chattogram">Chattogram</option>
                                                    <option value="Sylhet">Sylhet</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white">৳</span>
                                                <input type="number" class="form-control" name="price_max" placeholder="Max Price">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white">
                                                    <i class="bi bi-house-door"></i>
                                                </span>
                                                <select class="form-select" name="property_type">
                                                    <option value="">All Types</option>
                                                    <option value="single_room">Single Room</option>
                                                    <option value="sublet">Sublet</option>
                                                    <option value="full_flat">Full Flat</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="bi bi-search me-2"></i>Search
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
       
        <!-- Featured Properties with 3D Cards -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="h2 fw-bold" data-aos="fade-right">Featured Properties</h2>
                <p class="text-muted" data-aos="fade-right" data-aos-delay="100">Discover our most popular listings with 3D visualization</p>
            </div>
            <a href="search.php" class="btn btn-outline-primary px-4" data-aos="fade-left">
                <i class="bi bi-arrow-right me-2"></i>View All
            </a>
        </div>
        
        <div class="row">
            <?php foreach ($featured_properties as $index => $property): 
                // Generate a consistent image URL based on property ID
                $imageId = ($property['f_id'] % 10) + 1;
                $imageUrl = "https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80";
                
                // Different images based on property ID for variety
                switch($imageId) {
                    case 1: $imageUrl = "https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"; break;
                    case 2: $imageUrl = "https://images.unsplash.com/photo-1493692165395-d390a0d815ec?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"; break;
                    case 3: $imageUrl = "https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"; break;
                    case 4: $imageUrl = "https://images.unsplash.com/photo-1513956589380-bdc812197031?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"; break;
                    case 5: $imageUrl = "https://images.unsplash.com/photo-1502672260266-1c1ef2d936d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"; break;
                    case 6: $imageUrl = "https://images.unsplash.com/photo-1515263487990-61b07816b324?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"; break;
                    case 7: $imageUrl = "https://images.unsplash.com/photo-1518780664697-55e3ad937233?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"; break;
                    case 8: $imageUrl = "https://images.unsplash.com/photo-1564013799919-ab600027ffc6?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"; break;
                    case 9: $imageUrl = "https://images.unsplash.com/photo-1580587771525-78b9dba3b914?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"; break;
                    case 10: $imageUrl = "https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"; break;
                }
            ?>
                <div class="col-md-4 mb-5" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                    <div class="card card-3d glass-card property-card-3d h-100 cursor-fx">
                        <div class="property-image-3d" 
                             style="background-image: url('<?= $imageUrl ?>');">
                            <div class="position-absolute top-0 end-0 m-3 z-index-2">
                                <span class="badge bg-white text-primary badge-3d px-3 py-2 fs-6">
                                    <?= $property['availability'] ? 'Available' : 'Not Available' ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title h5 fw-bold mb-0"><?= htmlspecialchars($property['area']) ?></h5>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                            </div>
                            <p class="card-text text-muted small mb-3">
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
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <a href="property_details.php?f_id=<?= $property['f_id'] ?>" class="btn btn-gradient w-100 text-white py-3">
                                <i class="bi bi-eye me-2"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
        
        <!-- How It Works -->
        <section class="py-5 bg-light">
            <div class="container">
                <h2 class="text-center h3 fw-bold mb-5">How RentalNest Works</h2>
                <div class="row how-it-works">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="display-4 mb-3">1</div>
                                <h4 class="card-title h5 fw-bold">Search</h4>
                                <p class="card-text text-muted">Find properties that match your criteria with our powerful search filters tailored to your needs.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="display-4 mb-3">2</div>
                                <h4 class="card-title h5 fw-bold">Connect</h4>
                                <p class="card-text text-muted">Contact owners directly to schedule visits, ask questions, and get all the details you need.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="display-4 mb-3">3</div>
                                <h4 class="card-title h5 fw-bold">Book</h4>
                                <p class="card-text text-muted">Secure your booking with our easy reservation system for both short-term and long-term stays.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Testimonials -->
        <section class="py-5">
            <div class="container">
                <h2 class="text-center h3 fw-bold mb-5">What Our Users Say</h2>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px; font-weight: bold;">
                                            R
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">Rakibul Hasan</h5>
                                        <div class="text-warning">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-muted">"RentalNest made finding my apartment so easy! The search filters helped me find exactly what I needed near BRAC University."</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px; font-weight: bold;">
                                            A
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">Arif Hossain</h5>
                                        <div class="text-warning">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-half"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-muted">"As a property owner, I love how easy it is to list my flats and connect with potential renters. The booking system is seamless!"</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px; font-weight: bold;">
                                            S
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">Shamima Akter</h5>
                                        <div class="text-warning">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-muted">"The short-term booking option was perfect for my internship in Dhaka. I found a great place in Badda for just two weeks!"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle property image loading
        const propertyImages = document.querySelectorAll('.property-image-3d');
        
        propertyImages.forEach(imageContainer => {
            const imageUrl = imageContainer.style.backgroundImage.match(/url\("(.*?)"\)/)[1];
            
            // Add loading state
            imageContainer.classList.add('loading');
            
            // Create image to pre-load
            const img = new Image();
            img.src = imageUrl;
            
            img.onload = function() {
                imageContainer.classList.remove('loading');
                imageContainer.classList.add('loaded');
            };
            
            img.onerror = function() {
                // If image fails to load, use a fallback image
                imageContainer.style.backgroundImage = 'url("https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80")';
                imageContainer.classList.remove('loading');
                imageContainer.classList.add('loaded');
                
                // Show error message in dev mode
                console.error('Failed to load property image:', imageUrl);
            };
        });
    });
</script>
</body>
</html>