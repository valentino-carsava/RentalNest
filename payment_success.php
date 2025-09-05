<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if booking ID is provided
$booking_id = $_GET['booking_id'] ?? '';
if (empty($booking_id)) {
    header('Location: my_bookings.php');
    exit();
}

// Get booking details
$stmt = $pdo->prepare("SELECT b.*, f.area, f.district, f.street, f.price, 
                      o.name as owner_name, o.phone as owner_phone
                      FROM booking b
                      JOIN flat f ON b.owner_id = f.owner_id
                      JOIN owner o ON f.owner_id = o.owner_id
                      WHERE b.b_id = ? AND b.r_id = ?");
$stmt->execute([$booking_id, $_SESSION['user_id']]);
$booking = $stmt->fetch();

if (!$booking) {
    header('Location: my_bookings.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - RentalNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .confetti-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
        }
        
        .payment-success {
            background: linear-gradient(135deg, #f0f7ff 0%, #e6f7ff 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .success-icon {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            border-radius: 50%;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .success-icon i {
            font-size: 3rem;
            color: white;
        }
        
        .booking-details {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            flex: 1;
            min-width: 150px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="payment-success p-5 text-center">
                    <div class="success-icon mb-4">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    
                    <h1 class="display-4 fw-bold mb-3">Payment Successful!</h1>
                    <p class="lead mb-5">Your booking has been confirmed and payment processed successfully.</p>
                    
                    <div class="booking-details mb-5">
                        <div class="row g-3">
                            <div class="col-md-6 text-start">
                                <h3 class="h5 fw-bold mb-3">Booking Details</h3>
                                <p class="text-muted mb-2"><strong>Property:</strong> <?= htmlspecialchars($booking['area']) ?></p>
                                <p class="text-muted mb-2"><strong>Location:</strong> <?= htmlspecialchars($booking['district']) ?>, <?= htmlspecialchars($booking['street']) ?></p>
                                <p class="text-muted mb-2"><strong>Dates:</strong> <?= date('M d, Y', strtotime($booking['start_date'])) ?> to <?= date('M d, Y', strtotime($booking['end_date'])) ?></p>
                            </div>
                            <div class="col-md-6 text-start">
                                <h3 class="h5 fw-bold mb-3">Payment Details</h3>
                                <p class="text-muted mb-2"><strong>Amount:</strong> ৳<?= number_format($booking['price']) ?></p>
                                <p class="text-muted mb-2"><strong>Payment Method:</strong> Credit Card</p>
                                <p class="text-muted mb-2"><strong>Transaction ID:</strong> TXN-<?= strtoupper(substr(md5(time()), 0, 8)) ?></p>
                                <p class="text-muted mb-0"><strong>Date:</strong> <?= date('F j, Y, g:i a') ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="bg-light rounded-4 p-4 text-start">
                            <h3 class="h5 fw-bold mb-3">Next Steps</h3>
                            <ul class="list-unstyled">
                                <li class="d-flex mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                    <span>Your booking is now confirmed. The property owner has been notified.</span>
                                </li>
                                <li class="d-flex mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                    <span>You will receive a confirmation email with all booking details shortly.</span>
                                </li>
                                <li class="d-flex mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                    <span>Contact the owner directly for check-in details and any special requests.</span>
                                </li>
                                <li class="d-flex">
                                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                    <span>Save your booking confirmation for future reference.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="action-buttons mb-5">
                        <a href="my_bookings.php" class="btn btn-primary action-btn py-3">
                            <i class="bi bi-calendar-check me-2"></i>View All Bookings
                        </a>
                        <a href="property_details.php?f_id=<?= $booking['f_id'] ?>" class="btn btn-outline-primary action-btn py-3">
                            <i class="bi bi-house me-2"></i>View Property
                        </a>
                        <a href="owner_dashboard.php?owner_id=<?= $booking['owner_id'] ?>" class="btn btn-outline-primary action-btn py-3">
                            <i class="bi bi-person me-2"></i>Contact Owner
                        </a>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p class="text-muted">Thank you for choosing RentalNest. We wish you a comfortable stay!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="confetti-container" id="confetti-canvas"></div>
    
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confetti animation
            function fireConfetti() {
                confetti({
                    particleCount: 150,
                    spread: 70,
                    origin: { y: 0.6 }
                });
                
                // Additional bursts
                setTimeout(() => {
                    confetti({
                        particleCount: 100,
                        angle: 60,
                        spread: 55,
                        origin: { x: 0 }
                    });
                    confetti({
                        particleCount: 100,
                        angle: 120,
                        spread: 55,
                        origin: { x: 1 }
                    });
                }, 250);
                
                // Streamers
                setTimeout(() => {
                    confetti({
                        particleCount: 200,
                        startVelocity: 30,
                        angle: 60,
                        spread: 55,
                        origin: { x: 0 },
                        colors: ['#4361ee', '#3a0ca3', '#4cc9f0']
                    });
                    confetti({
                        particleCount: 200,
                        startVelocity: 30,
                        angle: 120,
                        spread: 55,
                        origin: { x: 1 },
                        colors: ['#4361ee', '#3a0ca3', '#4cc9f0']
                    });
                }, 500);
            }
            
            // Fire confetti on load
            fireConfetti();
            
            // Continue with occasional bursts
            setInterval(() => {
                confetti({
                    particleCount: 50,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            }, 3000);
        });
    </script>
</body>
</html>