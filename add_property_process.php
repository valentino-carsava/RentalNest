<?php
session_start();
include 'config.php';

// Check if user is logged in and is an owner
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'owner') {
    $_SESSION['error'] = 'You need to be logged in as a property owner to add a property.';
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $district = $_POST['district'] ?? '';
    $street = $_POST['street'] ?? '';
    $area = $_POST['area'] ?? '';
    $members_count = $_POST['members_count'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $availability = $_POST['availability'] ?? '1';
    $date_posted = date('Y-m-d');
    $owner_id = $_SESSION['user_id'];
    
    // Validate required fields
    if (empty($district) || empty($street) || empty($area) || empty($members_count) || 
        empty($price) || empty($description)) {
        $_SESSION['error'] = 'Please fill in all required fields.';
        header('Location: add_property.php');
        exit();
    }
    
    // Validate numeric fields
    if (!is_numeric($members_count) || !is_numeric($price)) {
        $_SESSION['error'] = 'Members count and price must be numeric values.';
        header('Location: add_property.php');
        exit();
    }
    
    try {
        // Begin transaction
        $pdo->beginTransaction();
        
        // Insert into flat table
        $sql = "INSERT INTO flat (availability, district, street, area, description, 
                members_count, price, date_posted, owner_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $availability,
            $district,
            $street,
            $area,
            $description,
            $members_count,
            $price,
            $date_posted,
            $owner_id
        ]);
        
        $f_id = $pdo->lastInsertId();
        
        // Create uploads directory if it doesn't exist
        $uploadDir = 'uploads/properties/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Handle image uploads (optional)
        if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxFiles = 5;
            $fileCount = 0;
            
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                if ($fileCount >= $maxFiles) break;
                
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $fileType = $_FILES['images']['type'][$i];
                    
                    if (in_array($fileType, $allowedTypes)) {
                        $fileName = uniqid('property_') . '_' . basename($_FILES['images']['name'][$i]);
                        $targetPath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetPath)) {
                            $fileCount++;
                        }
                    }
                }
            }
        }
        
        // Process visit availability data if provided
        $visitAvailability = $_POST['visit_availability'] ?? '';
        if (!empty($visitAvailability)) {
            $availabilityData = json_decode($visitAvailability, true);
            
            if (is_array($availabilityData) && count($availabilityData) > 0) {
                $stmt = $pdo->prepare("INSERT INTO visit_availability (f_id, day_of_week, start_time, end_time) 
                                      VALUES (?, ?, ?, ?)");
                
                foreach ($availabilityData as $slot) {
                    $stmt->execute([
                        $f_id,
                        $slot['day'],
                        $slot['start'],
                        $slot['end']
                    ]);
                }
            }
        }
        
        // Commit transaction
        $pdo->commit();
        
        $_SESSION['success'] = 'Property added successfully!';
        header('Location: property_details.php?f_id=' . $f_id);
        exit();
        
    } catch (PDOException $e) {
        // Rollback transaction on error
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        error_log("Property insertion error: " . $e->getMessage());
        $_SESSION['error'] = 'Error adding property: ' . $e->getMessage();
        header('Location: add_property.php');
        exit();
    }
} else {
    header('Location: add_property.php');
    exit();
}
?>