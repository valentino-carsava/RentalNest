<?php
session_start();
include 'config.php';

// Check if user is logged in and is a renter
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'renter') {
    $_SESSION['error'] = 'You need to be logged in as a renter to book a viewing.';
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $f_id = $_POST['f_id'] ?? '';
    $availability_id = $_POST['availability_id'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    
    // Validate required fields
    if (empty($f_id) || empty($availability_id) || empty($appointment_date)) {
        $_SESSION['error'] = 'Required information is missing.';
        header('Location: property_details.php?f_id=' . $f_id);
        exit();
    }
    
    // Validate date format (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $appointment_date)) {
        $_SESSION['error'] = 'Invalid date format.';
        header('Location: property_details.php?f_id=' . $f_id);
        exit();
    }
    
    // Validate that date is not in the past
    $appointmentDateTime = new DateTime($appointment_date);
    $today = new DateTime();
    $today->setTime(0, 0, 0);
    
    if ($appointmentDateTime < $today) {
        $_SESSION['error'] = 'Cannot book appointments for past dates.';
        header('Location: property_details.php?f_id=' . $f_id);
        exit();
    }
    
    try {
        // Begin transaction
        $pdo->beginTransaction();
        
        // Insert viewing appointment
        $stmt = $pdo->prepare("INSERT INTO viewing_appointments (availability_id, r_id, appointment_date) 
                              VALUES (?, ?, ?)");
        $stmt->execute([
            $availability_id,
            $_SESSION['user_id'],
            $appointment_date
        ]);
        
        // Commit transaction
        $pdo->commit();
        
        $_SESSION['success'] = 'Viewing appointment booked successfully!';
        header('Location: property_details.php?f_id=' . $f_id);
        exit();
        
    } catch (PDOException $e) {
        // Rollback transaction on error
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        error_log("Viewing appointment error: " . $e->getMessage());
        $_SESSION['error'] = 'Error booking viewing appointment: ' . $e->getMessage();
        header('Location: property_details.php?f_id=' . $f_id);
        exit();
    }
} else {
    $_SESSION['error'] = 'Invalid request method.';
    header('Location: search.php');
    exit();
}
?>