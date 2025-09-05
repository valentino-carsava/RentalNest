<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - RentalNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <!-- Hero Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-3 fw-bold mb-4">Building Homes, Not Just Houses</h1>
                    <p class="lead mb-5" style="opacity: 0.9;">RentalNest is revolutionizing the rental experience in Bangladesh with our innovative platform that connects property owners with renters seamlessly.</p>
                    <a href="search.php" class="btn btn-lg btn-light px-5 py-3 shadow-lg">
                        <i class="bi bi-search me-2"></i>Find Your Home
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Our Story -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                    <div class="position-relative">
                        <div class="bg-white rounded-4 overflow-hidden shadow-lg" style="height: 450px;">
                            <div class="w-100 h-100" style="background: url('https://images.unsplash.com/photo-1484154218962-a197022b5858?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80') center/cover;"></div>
                        </div>
                        <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                            <h3 class="text-white fw-bold mb-0">Our Journey Since 3 Weeks Ago</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <h2 class="h2 fw-bold mb-4">Our Story</h2>
                    <p class="text-muted mb-4">RentalNest was born from a simple yet powerful idea: to simplify the rental process in Bangladesh by creating a trustworthy platform that benefits both property owners and renters.</p>
                    <p class="text-muted mb-4">Founded in 2025 by a team of passionate individuals who experienced the challenges of finding and listing properties firsthand, we've grown to become one of the most trusted rental platforms in the country.</p>
                    <p class="text-muted mb-4">Today, thousands of users rely on RentalNest to find their perfect homes and list their properties with confidence, knowing they're part of a community that values transparency, security, and exceptional service.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Our Mission & Vision -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-6" data-aos="fade-up">
                <h2 class="h2 fw-bold mb-3">Our Mission & Vision</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">We're committed to transforming the rental experience in Bangladesh through innovation and trust</p>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4" data-aos="fade-right">
                    <div class="card glass-card border-0 shadow-sm h-100">
                        <div class="card-body p-5">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-4 d-inline-block mb-4">
                                <i class="bi bi-compass fs-2"></i>
                            </div>
                            <h3 class="h4 fw-bold mb-3">Our Mission</h3>
                            <p class="text-muted mb-0">To simplify the rental process by connecting property owners and renters through a transparent, secure, and user-friendly platform that provides exceptional value to all stakeholders.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4" data-aos="fade-left">
                    <div class="card glass-card border-0 shadow-sm h-100">
                        <div class="card-body p-5">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-4 d-inline-block mb-4">
                                <i class="bi bi-eye fs-2"></i>
                            </div>
                            <h3 class="h4 fw-bold mb-3">Our Vision</h3>
                            <p class="text-muted mb-0">To become the most trusted rental platform in Bangladesh, setting new standards for transparency, security, and user experience in the property rental industry.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Our Values -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-6" data-aos="fade-up">
                <h2 class="h2 fw-bold mb-3">Our Core Values</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">These principles guide everything we do at RentalNest</p>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card card-3d glass-card h-100 border-0 shadow-sm cursor-fx">
                        <div class="card-body p-4 text-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-4 d-inline-block mb-4" style="width: 80px; height: 80px;">
                                <i class="bi bi-shield-lock fs-2"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Trust & Security</h4>
                            <p class="text-muted mb-0">We prioritize the security of our users' information and transactions, building a platform where everyone feels safe and protected.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card card-3d glass-card h-100 border-0 shadow-sm cursor-fx">
                        <div class="card-body p-4 text-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-4 d-inline-block mb-4" style="width: 80px; height: 80px;">
                                <i class="bi bi-lightbulb fs-2"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Innovation</h4>
                            <p class="text-muted mb-0">We continuously innovate to improve the rental experience, leveraging technology to solve real-world problems for our users.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card card-3d glass-card h-100 border-0 shadow-sm cursor-fx">
                        <div class="card-body p-4 text-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-4 d-inline-block mb-4" style="width: 80px; height: 80px;">
                                <i class="bi bi-people fs-2"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Community</h4>
                            <p class="text-muted mb-0">We believe in building a strong community of property owners and renters who support each other and share valuable experiences.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
   
    
    <!-- Call to Action -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="glass-card p-5 text-center" data-aos="zoom-in">
                        <h2 class="h2 fw-bold mb-4">Ready to Find Your Perfect Home?</h2>
                        <p class="text-muted mb-5">Join thousands of satisfied users who have found their dream homes through RentalNest</p>
                        <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                            <a href="search.php" class="btn btn-gradient px-5 py-3 text-white fs-5">
                                <i class="bi bi-search me-2"></i>Search Properties
                            </a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php if ($_SESSION['user_type'] === 'owner'): ?>
                                    <a href="add_property.php" class="btn btn-outline-primary px-5 py-3 fs-5">
                                        <i class="bi bi-plus-circle me-2"></i>List Your Property
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="signup.php" class="btn btn-outline-primary px-5 py-3 fs-5">
                                    <i class="bi bi-plus-circle me-2"></i>Get Started
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>