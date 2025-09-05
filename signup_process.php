<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_type = $_POST['user_type'] ?? '';
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $address = $_POST['address'] ?? '';
    $gender = $_POST['gender'] ?? '';
    
    // Password validation
    if ($password !== $password_confirm) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: signup.php');
        exit();
    }
    
    if (strlen($password) < 6) {
        $_SESSION['error'] = 'Password must be at least 6 characters long.';
        header('Location: signup.php');
        exit();
    }
    
    // Common validation
    if (empty($name) || empty($phone) || empty($password) || empty($address) || empty($gender)) {
        $_SESSION['error'] = 'Please fill in all required fields.';
        header('Location: signup.php');
        exit();
    }
    
    try {
        if ($user_type === 'renter') {
            $st_id = $_POST['st_id'] ?? '';
            $institution = $_POST['institution'] ?? '';
            $room_preference = $_POST['room_preference'] ?? '';
            $type = 'Student'; // Default type for renters
            
            // Renter-specific validation
            if (empty($st_id) || empty($institution) || empty($room_preference)) {
                $_SESSION['error'] = 'Please fill in all renter-specific fields.';
                header('Location: signup.php');
                exit();
            }
            
            // Check if student ID already exists
            $stmt = $pdo->prepare("SELECT * FROM renter WHERE st_id = ?");
            $stmt->execute([$st_id]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Student ID already exists. Please use a different one.';
                header('Location: signup.php');
                exit();
            }
            
            // Insert into database
            $sql = "INSERT INTO renter (st_id, name, address, gender, room_preference, institution, type, phone) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $st_id,
                $name,
                $address,
                $gender,
                $room_preference,
                $institution,
                $type,
                $phone
            ]);
            
            $user_id = $pdo->lastInsertId();
            
            // Set session for auto-login
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_type'] = 'renter';
            $_SESSION['user_name'] = $name;
            
            $_SESSION['success'] = 'Renter account created successfully!';
            header('Location: renter_dashboard.php');
            exit();
        } 
        else if ($user_type === 'owner') {
            $email = $_POST['email'] ?? '';
            $nid = $_POST['nid'] ?? '';
            $owner_type = $_POST['owner_type'] ?? '';
            
            // Owner-specific validation
            if (empty($email) || empty($nid) || empty($owner_type)) {
                $_SESSION['error'] = 'Please fill in all owner-specific fields.';
                header('Location: signup.php');
                exit();
            }
            
            // Check if email or NID already exists
            $stmt = $pdo->prepare("SELECT * FROM owner WHERE email = ? OR NID = ?");
            $stmt->execute([$email, $nid]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Email or NID already exists. Please use different credentials.';
                header('Location: signup.php');
                exit();
            }
            
            // Insert into database
            $sql = "INSERT INTO owner (name, phone, email, NID, address, owner_type, gender) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $name,
                $phone,
                $email,
                $nid,
                $address,
                $owner_type,
                $gender
            ]);
            
            $user_id = $pdo->lastInsertId();
            
            // Set session for auto-login
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_type'] = 'owner';
            $_SESSION['owner_type'] = $owner_type;
            $_SESSION['user_name'] = $name;
            
            $_SESSION['success'] = 'Owner account created successfully!';
            header('Location: owner_dashboard.php');
            exit();
        }
        else {
            $_SESSION['error'] = 'Invalid user type.';
            header('Location: signup.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error creating account: ' . $e->getMessage();
        header('Location: signup.php');
        exit();
    }
} else {
    header('Location: signup.php');
    exit();
}
?>