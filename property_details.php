<?php
session_start();
include 'config.php';

// Get property ID from URL
if (!isset($_GET['f_id'])) {
    header('Location: search.php');
    exit();
}

$f_id = $_GET['f_id'];

// Get property details
$stmt = $pdo->prepare("SELECT f.*, o.name as owner_name, o.phone as owner_phone, o.email as owner_email 
                      FROM flat f 
                      JOIN owner o ON f.owner_id = o.owner_id 
                      WHERE f.f_id = ?");
$stmt->execute([$f_id]);
$property = $stmt->fetch();

if (!$property) {
    header('Location: search.php');
    exit();
}

// Check if the property is available for booking
$is_available = $property['availability'] == 1;

// Get reviews for this property
$stmt = $pdo->prepare("SELECT re.description as review_text, r.name as renter_name 
                      FROM gives g
                      JOIN reviews re ON g.rev_id = re.rev_id
                      JOIN renter r ON g.r_id = r.r_id
                      WHERE g.f_id = ?");
$stmt->execute([$f_id]);
$reviews = $stmt->fetchAll();

// Get visit availability for this property
$stmt = $pdo->prepare("SELECT * FROM visit_availability 
                      WHERE f_id = ? 
                      ORDER BY day_of_week, start_time");
$stmt->execute([$f_id]);
$visitAvailability = $stmt->fetchAll();

// Get number of interested renters
$stmt = $pdo->prepare("SELECT COUNT(*) as interest_count FROM interested_properties WHERE f_id = ?");
$stmt->execute([$f_id]);
$interestData = $stmt->fetch();
$interestCount = $interestData['interest_count'];

// Check if current renter is interested
$isInterested = false;
if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'renter') {
    $stmt = $pdo->prepare("SELECT * FROM interested_properties WHERE r_id = ? AND f_id = ?");
    $stmt->execute([$_SESSION['user_id'], $f_id]);
    $isInterested = $stmt->fetch() ? true : false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($property['area']) ?> - RentalNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .interest-count {
            background: #f0f7ff;
            border-left: 4px solid #4361ee;
            padding: 10px;
            border-radius: 0 4px 4px 0;
            margin-bottom: 20px;
        }
        
        .interest-btn {
            transition: all 0.3s ease;
        }
        
        .interest-btn.interest-active {
            color: #e53e3e;
            transform: scale(1.1);
        }
        
        .interest-btn:hover {
            color: #e53e3e;
            transform: scale(1.1);
        }
        
        .calendar-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-bottom: 10px;
        }
        
        .calendar-day-header {
            text-align: center;
            font-weight: bold;
            padding: 5px;
            background-color: #f1f5f9;
            border-radius: 4px;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }
        
        .calendar-date {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px;
            min-height: 80px;
            background-color: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .calendar-date:hover {
            background-color: #edf2f7;
            border-color: #cbd5e0;
        }
        
        .calendar-date.today {
            background-color: #dbeafe;
            border-color: #93c5fd;
            font-weight: bold;
        }
        
        .calendar-date.unavailable {
            background-color: #fdecea;
            border-color: #fbb4b4;
            cursor: not-allowed;
        }
        
        .calendar-date .date-number {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .time-slot {
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 5px;
            margin-bottom: 5px;
            font-size: 0.85rem;
            cursor: pointer;
        }
        
        .time-slot:hover {
            background-color: #ebf5ff;
            border-color: #93c5fd;
        }
        
        .time-slot.booked {
            background-color: #fee2e2;
            border-color: #fecaca;
            cursor: not-allowed;
        }
        
        .visit-card {
            border-left: 4px solid #4361ee;
            transition: all 0.2s ease;
        }
        
        .visit-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .appointment {
            background-color: #f0f7ff;
            border-left: 4px solid #4361ee;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 0 4px 4px 0;
        }
        
        .day-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="property-header mb-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h1 class="h2 fw-bold mb-2"><?= htmlspecialchars($property['area']) ?></h1>
                            <p class="text-muted">
                                <i class="bi bi-geo-alt me-1"></i>
                                <?= htmlspecialchars($property['district']) ?>, <?= htmlspecialchars($property['street']) ?>
                            </p>
                        </div>
                        <div class="text-end">
                            <h3 class="text-primary fw-bold">৳<?= number_format($property['price']) ?> <small class="fs-6 text-muted">/ month</small></h3>
                            <span class="badge bg-<?= $property['availability'] ? 'success' : 'secondary' ?> fs-6 mt-2">
                                <?= $property['availability'] ? 'Available Now' : 'Not Available' ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Interest Count -->
                <div class="interest-count">
                    <i class="bi bi-people me-2"></i>
                    <strong><?= $interestCount ?></strong> 
                    <?= $interestCount == 1 ? 'person is' : 'people are' ?> interested in this property
                </div>
                
                <!-- Property Images Carousel -->
                <div id="propertyCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                    <div class="carousel-inner rounded-4 overflow-hidden">
                        <div class="carousel-item active">
                            <div class="property-image" style="height: 450px; background-image: url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="property-image" style="height: 450px; background-image: url('https://images.unsplash.com/photo-1484154218962-a197022b5858?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="property-image" style="height: 450px; background-image: url('https://images.unsplash.com/photo-1513694203232-719a280e022f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');">
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                
                <!-- Interest Button -->
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'renter'): ?>
                    <div class="mb-4">
                        <button type="button" class="btn btn-outline-primary interest-btn <?= $isInterested ? 'interest-active' : '' ?>" 
                                id="interestBtn" 
                                data-f_id="<?= $property['f_id'] ?>">
                            <i class="bi <?= $isInterested ? 'bi-heart-fill' : 'bi-heart' ?> me-2"></i>
                            <?= $isInterested ? 'Interested' : 'Show Interest' ?>
                        </button>
                        <small class="text-muted ms-2">Save this property for later consideration</small>
                    </div>
                <?php endif; ?>
                
                <!-- Visit Availability Section -->
                <div class="mb-5">
                    <h3 class="h4 fw-bold mb-3 d-flex align-items-center">
                        <i class="bi bi-calendar-check me-2 text-primary"></i> Schedule a Viewing
                    </h3>
                    
                    <?php if (count($visitAvailability) > 0): ?>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'renter'): ?>
                            <div class="card glass-card border-0 shadow-sm p-4 mb-4">
                                <h5 class="fw-bold mb-3">Available Time Slots</h5>
                                
                                <div class="calendar-container">
                                    <div class="calendar-header">
                                        <button class="btn btn-outline-primary" id="prevMonth">
                                            <i class="bi bi-chevron-left"></i>
                                        </button>
                                        <h5 id="currentMonth">August 2025</h5>
                                        <button class="btn btn-outline-primary" id="nextMonth">
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="calendar-days">
                                        <div class="calendar-day-header">Sun</div>
                                        <div class="calendar-day-header">Mon</div>
                                        <div class="calendar-day-header">Tue</div>
                                        <div class="calendar-day-header">Wed</div>
                                        <div class="calendar-day-header">Thu</div>
                                        <div class="calendar-day-header">Fri</div>
                                        <div class="calendar-day-header">Sat</div>
                                    </div>
                                    
                                    <div class="calendar-grid" id="calendarGrid">
                                        <!-- Calendar will be generated by JavaScript -->
                                    </div>
                                </div>
                                
                                <div class="mt-4" id="timeSlotsContainer" style="display: none;">
                                    <h5 class="fw-bold mb-3">Available Time Slots for <span id="selectedDateDisplay"></span></h5>
                                    <div id="timeSlotsList">
                                        <!-- Time slots will be loaded here -->
                                    </div>
                                </div>
                                
                                <div id="bookingConfirmation" class="alert alert-success mt-4" style="display: none;">
                                    <h5 class="alert-heading"><i class="bi bi-check-circle me-2"></i>Appointment Booked!</h5>
                                    <p>Your viewing appointment has been scheduled. The property owner will contact you with confirmation.</p>
                                    <hr>
                                    <p class="mb-0">You can manage your appointments in your <a href="my_bookings.php" class="alert-link">My Bookings</a> section.</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Viewing Availability:</strong> 
                                <?php 
                                $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                $availabilityText = [];
                                
                                foreach ($visitAvailability as $slot) {
                                    $dayName = $dayNames[$slot['day_of_week']];
                                    $startTime = date('g:i A', strtotime($slot['start_time']));
                                    $endTime = date('g:i A', strtotime($slot['end_time']));
                                    $availabilityText[] = "$dayName: $startTime - $endTime";
                                }
                                
                                echo implode(', ', $availabilityText);
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'owner' && $_SESSION['user_id'] == $property['owner_id']): ?>
                            <div class="card glass-card border-0 shadow-sm p-4 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0">Upcoming Viewings</h5>
                                    <span class="badge bg-primary"><?= count($upcomingAppointments) ?></span>
                                </div>
                                
                                <?php if (count($upcomingAppointments) > 0): ?>
                                    <?php foreach ($upcomingAppointments as $appointment): ?>
                                        <div class="appointment">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong><?= date('F j, Y', strtotime($appointment['appointment_date'])) ?></strong>
                                                    <div class="text-muted">
                                                        <?= date('g:i A', strtotime($appointment['start_time'])) ?> - 
                                                        <?= date('g:i A', strtotime($appointment['end_time'])) ?>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="fw-bold"><?= htmlspecialchars($appointment['renter_name']) ?></div>
                                                    <div class="text-muted"><?= htmlspecialchars($appointment['renter_phone']) ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">No upcoming viewing appointments.</p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            The owner has not set specific viewing availability. Please contact the owner to schedule a viewing.
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="property-details mb-4">
                    <h3 class="h4 fw-bold mb-3">Property Details</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="text-primary me-3">
                                    <i class="bi bi-geo-alt fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Location</h5>
                                    <p class="text-muted mb-0">
                                        <?= htmlspecialchars($property['district']) ?>, <?= htmlspecialchars($property['street']) ?><br>
                                        Area: <?= htmlspecialchars($property['area']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="text-primary me-3">
                                    <i class="bi bi-people fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Roommates</h5>
                                    <p class="text-muted mb-0">
                                        This property accommodates up to <?= htmlspecialchars($property['members_count']) ?> people.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="text-primary me-3">
                                    <i class="bi bi-calendar-check fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Availability</h5>
                                    <p class="text-muted mb-0">
                                        <?= $property['availability'] ? 'Available immediately' : 'Not currently available' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="text-primary me-3">
                                    <i class="bi bi-clock fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Posted On</h5>
                                    <p class="text-muted mb-0">
                                        <?= date('F j, Y', strtotime($property['date_posted'])) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h4 class="fw-bold mb-3">Description</h4>
                        <p class="text-muted"><?= nl2br(htmlspecialchars($property['description'])) ?></p>
                    </div>
                </div>
                
                <div class="property-features mb-4">
                    <h4 class="fw-bold mb-3">Property Features</h4>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="feature-item">
                                <i class="bi bi-wifi"></i>
                                <span>High-speed WiFi</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-item">
                                <i class="bi bi-car-front"></i>
                                <span>Parking Available</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-item">
                                <i class="bi bi-shield-lock"></i>
                                <span>24/7 Security</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-item">
                                <i class="bi bi-house"></i>
                                <span>Fully Furnished</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-item">
                                <i class="bi bi-lightning-charge"></i>
                                <span>Utilities Included</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-item">
                                <i class="bi bi-tree"></i>
                                <span>Balcony</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (count($reviews) > 0): ?>
                <div class="property-reviews mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h4 fw-bold mb-0">Reviews</h3>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                            <span class="ms-2">4.5 (<?= count($reviews) ?> reviews)</span>
                        </div>
                    </div>
                    
                    <?php foreach ($reviews as $review): ?>
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title fw-bold"><?= htmlspecialchars($review['renter_name']) ?></h5>
                                        <div class="text-warning mb-2">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                    </div>
                                    <small class="text-muted">Reviewed on <?= date('M d, Y') ?></small>
                                </div>
                                <p class="card-text text-muted"><?= htmlspecialchars($review['review_text']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-body p-4">
                        <h4 class="h5 fw-bold mb-4">Contact Owner</h4>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px;">
                                <i class="bi bi-person fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0"><?= htmlspecialchars($property['owner_name']) ?></h5>
                                <p class="text-muted mb-0">Property Owner</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-telephone fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Phone</h6>
                                    <p class="text-muted mb-0"><?= htmlspecialchars($property['owner_phone']) ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-envelope fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Email</h6>
                                    <p class="text-muted mb-0"><?= htmlspecialchars($property['owner_email']) ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($is_available): ?>
                            <a href="book_property.php?f_id=<?= $property['f_id'] ?>" class="btn btn-primary w-100 mb-2 py-3">
                                <i class="bi bi-calendar-check me-2"></i>Book Now
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100 mb-2 py-3" disabled>
                                <i class="bi bi-calendar-x me-2"></i>Not Available
                            </button>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'renter'): ?>
                            <a href="#" class="btn btn-outline-primary w-100 py-3" data-bs-toggle="modal" data-bs-target="#scheduleViewingModal">
                                <i class="bi bi-calendar-event me-2"></i>Schedule Viewing
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-body p-4">
                        <h5 class="h6 fw-bold mb-3">Short-Term Booking Available</h5>
                        <p class="text-muted mb-3">Need this property for just a few days? We offer flexible short-term bookings.</p>
                        <ul class="list-unstyled mb-3">
                            <li class="d-flex mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>2-7 day bookings available</span>
                            </li>
                            <li class="d-flex mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>1.5x monthly rate per day</span>
                            </li>
                            <li class="d-flex">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>Flexible check-in/check-out times</span>
                            </li>
                        </ul>
                        <a href="book_property.php?f_id=<?= $property['f_id'] ?>" class="btn btn-outline-primary w-100 py-2">
                            <i class="bi bi-calendar-range me-2"></i>Book Short-Term
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Interest button functionality
            const interestBtn = document.getElementById('interestBtn');
            if (interestBtn) {
                interestBtn.addEventListener('click', function() {
                    const f_id = this.getAttribute('data-f_id');
                    
                    fetch('interest_process.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'f_id=' + f_id
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Toggle button state
                            if (data.action === 'added') {
                                interestBtn.classList.add('interest-active');
                                interestBtn.innerHTML = '<i class="bi bi-heart-fill me-2"></i>Interested';
                                interestBtn.classList.remove('btn-outline-primary');
                                interestBtn.classList.add('btn-primary');
                            } else {
                                interestBtn.classList.remove('interest-active');
                                interestBtn.innerHTML = '<i class="bi bi-heart me-2"></i>Show Interest';
                                interestBtn.classList.remove('btn-primary');
                                interestBtn.classList.add('btn-outline-primary');
                            }
                            
                            // Show success message
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                            alertDiv.role = 'alert';
                            alertDiv.innerHTML = data.message + ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                            interestBtn.parentElement.appendChild(alertDiv);
                            
                            // Update interest count
                            const interestCount = document.querySelector('.interest-count strong');
                            if (data.action === 'added') {
                                interestCount.textContent = parseInt(interestCount.textContent) + 1;
                            } else {
                                interestCount.textContent = Math.max(0, parseInt(interestCount.textContent) - 1);
                            }
                        } else {
                            // Show error message
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                            alertDiv.role = 'alert';
                            alertDiv.innerHTML = data.message + ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                            interestBtn.parentElement.appendChild(alertDiv);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                        alertDiv.role = 'alert';
                        alertDiv.innerHTML = 'Error processing interest. Please try again. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                        interestBtn.parentElement.appendChild(alertDiv);
                    });
                });
            }
            
            // Calendar functionality for the main page
            const today = new Date();
            let currentMonth = today.getMonth();
            let currentYear = today.getFullYear();
            
            // Function to generate calendar
            function generateCalendar(month, year) {
                const calendarGrid = document.getElementById('calendarGrid');
                calendarGrid.innerHTML = '';
                
                // Get first day of month
                const firstDay = new Date(year, month, 1);
                const startingDay = firstDay.getDay(); // 0 = Sunday, 1 = Monday, etc.
                
                // Get total days in month
                const lastDay = new Date(year, month + 1, 0);
                const totalDays = lastDay.getDate();
                
                // Fill in previous month's days (grayed out)
                for (let i = 0; i < startingDay; i++) {
                    const dateCell = document.createElement('div');
                    dateCell.className = 'calendar-date disabled';
                    dateCell.innerHTML = `<span class="date-number">${new Date(year, month, 0 - i).getDate()}</span>`;
                    calendarGrid.appendChild(dateCell);
                }
                
                // Fill in current month's days
                for (let i = 1; i <= totalDays; i++) {
                    const dateCell = document.createElement('div');
                    dateCell.className = 'calendar-date';
                    dateCell.dataset.date = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                    
                    // Check if this is today
                    if (i === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                        dateCell.classList.add('today');
                    }
                    
                    // Check if date is in the past
                    const dateToCheck = new Date(year, month, i);
                    if (dateToCheck < new Date(today.getFullYear(), today.getMonth(), today.getDate())) {
                        dateCell.classList.add('unavailable');
                    }
                    
                    dateCell.innerHTML = `<span class="date-number">${i}</span>`;
                    
                    // Add click event to show time slots
                    dateCell.addEventListener('click', function() {
                        if (this.classList.contains('unavailable')) return;
                        
                        document.querySelectorAll('.calendar-date').forEach(d => d.classList.remove('selected'));
                        this.classList.add('selected');
                        
                        const selectedDate = this.dataset.date;
                        document.getElementById('selectedDateDisplay').textContent = formatDate(selectedDate);
                        
                        // Show time slots container
                        document.getElementById('timeSlotsContainer').style.display = 'block';
                        
                        // Load available time slots for this date
                        loadTimeSlots(selectedDate);
                    });
                    
                    calendarGrid.appendChild(dateCell);
                }
                
                // Fill in next month's days to complete the grid
                const daysAdded = startingDay + totalDays;
                const remainingCells = 42 - daysAdded; // 6 rows of 7 days
                
                for (let i = 1; i <= remainingCells; i++) {
                    const dateCell = document.createElement('div');
                    dateCell.className = 'calendar-date disabled';
                    dateCell.innerHTML = `<span class="date-number">${i}</span>`;
                    calendarGrid.appendChild(dateCell);
                }
                
                // Update month display
                const monthNames = ["January", "February", "March", "April", "May", "June",
                                   "July", "August", "September", "October", "November", "December"];
                document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
            }
            
            // Function to load time slots for a specific date
            function loadTimeSlots(date) {
                const timeSlotsList = document.getElementById('timeSlotsList');
                timeSlotsList.innerHTML = '<div class="text-center py-3">Loading available time slots...</div>';
                
                // In a real application, this would be an AJAX call to the server
                // For this example, we'll simulate it with a timeout
                setTimeout(() => {
                    const dayOfWeek = new Date(date).getDay(); // 0 = Sunday, 1 = Monday, etc.
                    
                    // Filter availability for this day of week
                    const availability = <?php echo json_encode($visitAvailability); ?>;
                    const dayAvailability = availability.filter(slot => slot.day_of_week == dayOfWeek);
                    
                    if (dayAvailability.length === 0) {
                        timeSlotsList.innerHTML = '<div class="alert alert-info">No viewing availability set for this day.</div>';
                        return;
                    }
                    
                    let slotsHTML = '';
                    
                    // Generate time slots based on availability
                    dayAvailability.forEach(slot => {
                        const startTime = new Date(`1970-01-01T${slot.start_time}`);
                        const endTime = new Date(`1970-01-01T${slot.end_time}`);
                        
                        // Generate 30-minute intervals
                        let currentTime = new Date(startTime);
                        while (currentTime < endTime) {
                            const nextTime = new Date(currentTime);
                            nextTime.setMinutes(nextTime.getMinutes() + 30);
                            
                            if (nextTime <= endTime) {
                                const formattedTime = `${formatTime(currentTime)} - ${formatTime(nextTime)}`;
                                slotsHTML += `
                                    <div class="time-slot" 
                                         data-date="${date}" 
                                         data-start="${formatTimeForDB(currentTime)}"
                                         data-end="${formatTimeForDB(nextTime)}">
                                        ${formattedTime}
                                    </div>
                                `;
                            }
                            
                            currentTime = nextTime;
                        }
                    });
                    
                    if (slotsHTML === '') {
                        timeSlotsList.innerHTML = '<div class="alert alert-info">No available time slots for this date.</div>';
                    } else {
                        timeSlotsList.innerHTML = slotsHTML;
                        
                        // Add click events to time slots
                        document.querySelectorAll('.time-slot').forEach(slot => {
                            slot.addEventListener('click', function() {
                                const date = this.dataset.date;
                                const startTime = this.dataset.start;
                                const endTime = this.dataset.end;
                                
                                // In a real application, this would be an AJAX call to book the appointment
                                // For this example, we'll just show a confirmation
                                document.getElementById('bookingConfirmation').style.display = 'block';
                                
                                // Scroll to confirmation
                                document.getElementById('bookingConfirmation').scrollIntoView({
                                    behavior: 'smooth'
                                });
                            });
                        });
                    }
                }, 300);
            }
            
            // Helper function to format time
            function formatTime(date) {
                return date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            }
            
            // Helper function to format time for database
            function formatTimeForDB(date) {
                return date.toTimeString().substring(0, 5);
            }
            
            // Helper function to format date for display
            function formatDate(dateString) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString(undefined, options);
            }
            
            // Initialize calendar
            generateCalendar(currentMonth, currentYear);
            
            // Previous month button
            document.getElementById('prevMonth').addEventListener('click', function() {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                generateCalendar(currentMonth, currentYear);
            });
            
            // Next month button
            document.getElementById('nextMonth').addEventListener('click', function() {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                generateCalendar(currentMonth, currentYear);
            });
        });
    </script>
</body>
</html>