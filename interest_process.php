<?php
session_start();
include 'config.php';

// Check if user is logged in and is a renter
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'renter') {
    echo json_encode(['success' => false, 'message' => 'You need to be logged in as a renter to show interest.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $f_id = $_POST['f_id'] ?? '';
    
    if (empty($f_id)) {
        echo json_encode(['success' => false, 'message' => 'Property ID is required.']);
        exit();
    }
    
    try {
        // Check if already interested
        $stmt = $pdo->prepare("SELECT * FROM interested_properties WHERE r_id = ? AND f_id = ?");
        $stmt->execute([$_SESSION['user_id'], $f_id]);
        $isInterested = $stmt->fetch();
        
        if ($isInterested) {
            // Remove interest
            $stmt = $pdo->prepare("DELETE FROM interested_properties WHERE r_id = ? AND f_id = ?");
            $stmt->execute([$_SESSION['user_id'], $f_id]);
            
            echo json_encode([
                'success' => true,
                'action' => 'removed',
                'message' => 'Removed from your interested properties.'
            ]);
        } else {
            // Add interest
            $stmt = $pdo->prepare("INSERT INTO interested_properties (r_id, f_id) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $f_id]);
            
            echo json_encode([
                'success' => true,
                'action' => 'added',
                'message' => 'Added to your interested properties!'
            ]);
        }
        exit();
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error processing interest: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}
?>