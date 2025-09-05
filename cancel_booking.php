<?php
session_start();
include 'config.php';

// Check if user is logged in and is a renter
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'renter') {
    $_SESSION['error'] = 'You need to be logged in as a renter to cancel a booking.';
    header('Location: login.php');
    exit();
}

// Get booking ID from URL
if (!isset($_GET['b_id'])) {
    $_SESSION['error'] = 'Booking ID is required.';
    header('Location: my_bookings.php');
    exit();
}

$b_id = $_GET['b_id'];
$f_id = $_GET['f_id'] ?? null;

try {
    // Verify the booking belongs to the current user
    $stmt = $pdo->prepare("SELECT * FROM booking WHERE b_id = ? AND r_id = ?");
    $stmt->execute([$b_id, $_SESSION['user_id']]);
    $booking = $stmt->fetch();
    
    if (!$booking) {
        $_SESSION['error'] = 'Booking not found or you do not have permission to cancel this booking.';
        header('Location: my_bookings.php');
        exit();
    }
    
    // Check if the booking is upcoming (only upcoming bookings can be canceled)
    $currentDate = new DateTime();
    $startDate = new DateTime($booking['start_date']);
    
    if ($currentDate > $startDate) {
        $_SESSION['error'] = 'You cannot cancel a booking that has already started.';
        header('Location: my_bookings.php');
        exit();
    }
    
    // Delete the booking
    $stmt = $pdo->prepare("DELETE FROM booking WHERE b_id = ?");
    $stmt->execute([$b_id]);
    
    // Update the specific flat's availability to available
    if ($f_id) {
        $stmt = $pdo->prepare("UPDATE flat SET availability = 1 WHERE f_id = ?");
        $stmt->execute([$f_id]);
    }
    
    $_SESSION['success'] = 'Booking canceled successfully! The property is now available for others.';
    header('Location: my_bookings.php');
    exit();
    
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error canceling booking: ' . $e->getMessage();
    header('Location: my_bookings.php');
    exit();
}
?>