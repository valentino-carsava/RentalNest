<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_type = $_POST['user_type'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // For demonstration purposes, we're using a simple password check
    // In a real application, you would hash and verify passwords securely
    $demo_password = 'password123';
    
    if ($user_type === 'renter') {
        $renter_id = $_POST['renter_id'] ?? '';
        
        // Check if it's a renter (using st_id)
        $stmt = $pdo->prepare("SELECT * FROM renter WHERE st_id = ?");
        $stmt->execute([$renter_id]);
        $renter = $stmt->fetch();
        
        if ($renter && $password === $demo_password) {
            $_SESSION['user_id'] = $renter['r_id'];
            $_SESSION['user_type'] = 'renter';
            $_SESSION['user_name'] = $renter['name'];
            
            header('Location: renter_dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = 'Invalid student ID or password';
        }
    } 
    else if ($user_type === 'owner') {
        $email = $_POST['email'] ?? '';
        
        // Check if it's an owner (using email)
        $stmt = $pdo->prepare("SELECT * FROM owner WHERE email = ?");
        $stmt->execute([$email]);
        $owner = $stmt->fetch();
        
        if ($owner && $password === $demo_password) {
            $_SESSION['user_id'] = $owner['owner_id'];
            $_SESSION['user_type'] = 'owner';
            $_SESSION['owner_type'] = $owner['owner_type'];
            $_SESSION['user_name'] = $owner['name'];
            
            header('Location: owner_dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = 'Invalid email or password';
        }
    }
    
    // If no match found or invalid user type
    header('Location: login.php');
    exit();
} else {
    header('Location: login.php');
    exit();
}
?>