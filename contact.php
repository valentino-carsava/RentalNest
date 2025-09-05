<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - RentalNest</title>
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
                    <h1 class="display-3 fw-bold mb-4">We'd Love to Hear From You</h1>
                    <p class="lead mb-5" style="opacity: 0.9;">Have questions about RentalNest? Reach out to us and we'll get back to you as soon as possible.</p>
                </div>
            </div>
        </div>
    </section>
    
    <div class="container py-5">
        <div class="row mb-5">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="card glass-card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h2 class="h3 fw-bold mb-4">Get In Touch</h2>
                        
                        <div class="d-flex mb-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-geo-alt fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Our Location</h5>
                                <p class="text-muted mb-0">House #25, Road #12, Dhanmondi<br>Dhaka 1209, Bangladesh</p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-telephone fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Phone Number</h5>
                                <p class="text-muted mb-0">+880 1712 345 678<br>+880 1912 345 678</p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-envelope fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Email Address</h5>
                                <p class="text-muted mb-0">support@rentalnest.com<br>info@rentalnest.com</p>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-clock fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Working Hours</h5>
                                <p class="text-muted mb-0">Monday - Friday: 9:00 AM - 8:00 PM<br>Saturday: 10:00 AM - 4:00 PM<br>Sunday: Closed</p>
                            </div>
                        </div>
                        
                        <div class="mt-5">
                            <h3 class="h4 fw-bold mb-3">Follow Us</h3>
                            <div class="d-flex gap-3">
                                <a href="#" class="text-decoration-none">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3" style="width: 50px; height: 50px;">
                                        <i class="bi bi-facebook fs-4"></i>
                                    </div>
                                </a>
                                <a href="#" class="text-decoration-none">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3" style="width: 50px; height: 50px;">
                                        <i class="bi bi-twitter fs-4"></i>
                                    </div>
                                </a>
                                <a href="#" class="text-decoration-none">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3" style="width: 50px; height: 50px;">
                                        <i class="bi bi-instagram fs-4"></i>
                                    </div>
                                </a>
                                <a href="#" class="text-decoration-none">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3" style="width: 50px; height: 50px;">
                                        <i class="bi bi-linkedin fs-4"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="card glass-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h3 fw-bold mb-4">Send Us a Message</h2>
                        
                        <?php if(isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $_SESSION['success'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        
                        <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $_SESSION['error'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        
                        <form action="contact_process.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-bold">Full Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="bi bi-person"></i>
                                            </span>
                                            <input type="text" class="form-control" id="name" name="name" required 
                                                   placeholder="Enter your full name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-bold">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control" id="email" name="email" required 
                                                   placeholder="Enter your email address">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label fw-bold">Phone Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="bi bi-telephone"></i>
                                            </span>
                                            <input type="tel" class="form-control" id="phone" name="phone" required 
                                                   placeholder="Enter your phone number">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="subject" class="form-label fw-bold">Subject</label>
                                        <select class="form-select" id="subject" name="subject" required>
                                            <option value="">Select Subject</option>
                                            <option value="general">General Inquiry</option>
                                            <option value="support">Technical Support</option>
                                            <option value="partnership">Partnership Opportunity</option>
                                            <option value="feedback">Feedback</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label fw-bold">Your Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required 
                                          placeholder="Type your message here..."></textarea>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-gradient text-white py-3">
                                    <i class="bi bi-send me-2"></i>Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="mt-4">
                    <div class="card glass-card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h4 fw-bold mb-3">Frequently Asked Questions</h3>
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            How do I list my property on RentalNest?
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            To list your property, simply create an owner account, log in to your dashboard, and click on "Add Property". Follow the steps to provide all necessary details about your property, upload photos, and set your pricing and availability.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Is there a fee for using RentalNest?
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Creating an account and searching for properties is completely free for renters. Property owners can list their properties for free, but we charge a small service fee for each successful booking made through our platform.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            How can I contact customer support?
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            You can contact our customer support team through this contact form, by email at support@rentalnest.com, or by phone at +880 1712 345 678. Our support team is available Monday through Friday from 9 AM to 8 PM.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Google Map -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="h2 fw-bold mb-3">Find Us Here</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">Our office is conveniently located in the heart of Dhanmondi, Dhaka</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="glass-card rounded-4 overflow-hidden shadow-sm" style="height: 450px;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.345524131913!2d90.3638199144315!3d23.78061259349585!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c7b4e3d7b0e5%3A0x6d8d1e0c1c5c6e2d!2sDhanmondi%2C%20Dhaka!5e0!3m2!1sen!2sbd!4v1691313500000!5m2!1sen!2sbd" 
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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