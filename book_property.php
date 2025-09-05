<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if user is a renter
if ($_SESSION['user_type'] !== 'renter') {
    $_SESSION['error'] = 'Only renters can book properties.';
    header('Location: index.php');
    exit();
}

// Get property ID from URL
if (!isset($_GET['f_id'])) {
    header('Location: search.php');
    exit();
}

$f_id = $_GET['f_id'];

// Get property details
$stmt = $pdo->prepare("SELECT f.*, o.owner_id, o.name as owner_name 
                      FROM flat f 
                      JOIN owner o ON f.owner_id = o.owner_id 
                      WHERE f.f_id = ?");
$stmt->execute([$f_id]);
$property = $stmt->fetch();

if (!$property) {
    header('Location: search.php');
    exit();
}

// Check if property is available
if ($property['availability'] != 1) {
    $_SESSION['error'] = 'This property is not available for booking.';
    header('Location: property_details.php?f_id=' . $f_id);
    exit();
}

// Process booking if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    
    // Validate dates
    if (empty($start_date) || empty($end_date)) {
        $_SESSION['error'] = 'Please select both start and end dates.';
        header('Location: book_property.php?f_id=' . $f_id);
        exit();
    }
    
    $start_date = date('Y-m-d', strtotime($start_date));
    $end_date = date('Y-m-d', strtotime($end_date));
    
    // Check if start date is in the past
    if (strtotime($start_date) < time()) {
        $_SESSION['error'] = 'Start date cannot be in the past.';
        header('Location: book_property.php?f_id=' . $f_id);
        exit();
    }
    
    // Check if end date is before start date
    if (strtotime($end_date) <= strtotime($start_date)) {
        $_SESSION['error'] = 'End date must be after start date.';
        header('Location: book_property.php?f_id=' . $f_id);
        exit();
    }
    
    try {
        // Begin transaction
        $pdo->beginTransaction();
        
        // Insert booking
        $sql = "INSERT INTO booking (start_date, end_date, f_id, r_id, owner_id) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $start_date,
            $end_date,
            $f_id,
            $_SESSION['user_id'],
            $property['owner_id']
        ]);
        
        $booking_id = $pdo->lastInsertId();
        
        // Update property availability
        $stmt = $pdo->prepare("UPDATE flat SET availability = 0 WHERE f_id = ?");
        $stmt->execute([$f_id]);
        
        // Commit transaction
        $pdo->commit();
        
        // Redirect to payment success page
        header('Location: payment_success.php?booking_id=' . $booking_id);
        exit();
        
    } catch (PDOException $e) {
        // Rollback transaction on error
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        error_log("Booking error: " . $e->getMessage());
        $_SESSION['error'] = 'Error creating booking: ' . $e->getMessage();
        header('Location: book_property.php?f_id=' . $f_id);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Property - RentalNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .booking-card {
            border-left: 4px solid #4361ee;
            transition: all 0.2s ease;
        }
        
        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .date-picker {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
        }
        
        .date-input {
            position: relative;
        }
        
        .date-input i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #4a5568;
        }
        
        .date-input input {
            padding-left: 40px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white py-4">
                        <h3 class="text-center mb-0">Book Property</h3>
                        <p class="text-center mb-0 opacity-75">Complete your booking for <?= htmlspecialchars($property['area']) ?></p>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $_SESSION['error'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        
                        <div class="booking-card p-4 mb-4 bg-light rounded-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px;">
                                    <i class="bi bi-house fs-5"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0"><?= htmlspecialchars($property['area']) ?></h5>
                                    <p class="text-muted mb-0">
                                        <?= htmlspecialchars($property['district']) ?>, <?= htmlspecialchars($property['street']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <form action="book_property.php?f_id=<?= $f_id ?>" method="POST">
                            <div class="date-picker mb-4">
                                <h4 class="h5 fw-bold mb-3 d-flex align-items-center">
                                    <i class="bi bi-calendar-event me-2 text-primary"></i> Select Dates
                                </h4>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="date-input">
                                            <i class="bi bi-calendar-event"></i>
                                            <input type="date" class="form-control form-control-lg" id="start_date" name="start_date" required 
                                                   min="<?= date('Y-m-d') ?>">
                                        </div>
                                        <label for="start_date" class="form-label fw-bold mt-2">Check-in Date</label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="date-input">
                                            <i class="bi bi-calendar-event"></i>
                                            <input type="date" class="form-control form-control-lg" id="end_date" name="end_date" required 
                                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                        </div>
                                        <label for="end_date" class="form-label fw-bold mt-2">Check-out Date</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card border-0 shadow-sm p-4 mb-4">
                                <h4 class="h5 fw-bold mb-3 d-flex align-items-center">
                                    <i class="bi bi-wallet me-2 text-primary"></i> Payment Details
                                </h4>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="card_number" class="form-label fw-bold">Card Number</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white">
                                                    <i class="bi bi-credit-card"></i>
                                                </span>
                                                <input type="text" class="form-control form-control-lg" id="card_number" 
                                                       placeholder="XXXX XXXX XXXX XXXX" required
                                                       pattern="\d{4} \d{4} \d{4} \d{4}" 
                                                       title="Please enter 16 digits in format: XXXX XXXX XXXX XXXX">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="expiry_date" class="form-label fw-bold">Expiry Date</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white">
                                                    <i class="bi bi-calendar"></i>
                                                </span>
                                                <input type="text" class="form-control form-control-lg" id="expiry_date" 
                                                       placeholder="MM/YY" required
                                                       pattern="(0[1-9]|1[0-2])\/([0-9]{2})" 
                                                       title="Please enter date in MM/YY format">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="cvv" class="form-label fw-bold">CVV</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white">
                                                    <i class="bi bi-lock"></i>
                                                </span>
                                                <input type="text" class="form-control form-control-lg" id="cvv" 
                                                       placeholder="XXX" required
                                                       pattern="\d{3}" 
                                                       title="Please enter 3 digits CVV">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 p-3 bg-light rounded-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Property Price (per month):</span>
                                        <span class="fw-bold">৳<?= number_format($property['price']) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Service Fee:</span>
                                        <span class="fw-bold">৳<?= number_format($property['price'] * 0.05) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Security Deposit:</span>
                                        <span class="fw-bold">৳<?= number_format($property['price'] * 0.2) ?></span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Total Amount:</span>
                                        <span class="fw-bold text-primary fs-5">৳<?= number_format($property['price'] * 1.25) ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg py-3">
                                    <i class="bi bi-credit-card me-2"></i>Complete Booking & Pay ৳<?= number_format($property['price'] * 1.25) ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set min date for start_date to today
            const today = new Date();
            const minDate = today.toISOString().split('T')[0];
            document.getElementById('start_date').min = minDate;
            
            // Update end_date min when start_date changes
            document.getElementById('start_date').addEventListener('change', function() {
                const startDate = new Date(this.value);
                const endDateInput = document.getElementById('end_date');
                
                // Set min date for end_date to start_date + 1 day
                const nextDay = new Date(startDate);
                nextDay.setDate(startDate.getDate() + 1);
                endDateInput.min = nextDay.toISOString().split('T')[0];
                
                // If end_date is before the new min, reset it
                if (endDateInput.value && new Date(endDateInput.value) <= startDate) {
                    endDateInput.value = '';
                }
            });
        });
    </script>
</body>
</html>