<?php
session_start();
include 'config.php';

// Check if user is logged in and is a renter
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'renter') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $f_id = $_POST['f_id'] ?? '';
    $booking_type = $_POST['booking_type'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $special_requests = $_POST['special_requests'] ?? '';
    
    // Validate required fields
    if (empty($f_id) || empty($booking_type) || empty($start_date) || empty($end_date)) {
        $_SESSION['error'] = 'Please fill in all required fields.';
        header('Location: book_property.php?f_id=' . $f_id);
        exit();
    }
    
    // Get property details
    $stmt = $pdo->prepare("SELECT * FROM flat WHERE f_id = ?");
    $stmt->execute([$f_id]);
    $property = $stmt->fetch();
    
    if (!$property) {
        $_SESSION['error'] = 'Property not found.';
        header('Location: search.php');
        exit();
    }
    
    try {
        // Insert into booking table
        $sql = "INSERT INTO booking (start_date, end_date, r_id, owner_id) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $start_date,
            $end_date,
            $_SESSION['user_id'],
            $property['owner_id']
        ]);
        
        // Update property availability if it's a long-term booking
        if ($booking_type === 'monthly') {
            $updateSql = "UPDATE flat SET availability = 0 WHERE f_id = ?";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([$f_id]);
        }
        
        $_SESSION['success'] = 'Booking confirmed successfully!';
        header('Location: my_bookings.php');
        exit();
        
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error processing booking: ' . $e->getMessage();
        header('Location: book_property.php?f_id=' . $f_id);
        exit();
    }
} else {
    header('Location: search.php');
    exit();
}
?>