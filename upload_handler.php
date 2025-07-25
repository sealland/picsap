<?php
// Set error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "<div style='padding:2em;text-align:center; color:red;'>Method not allowed</div>";
    exit;
}

// Get the material ID
$material_id = $_POST['material_id'] ?? '';

if (empty($material_id)) {
    echo "<div style='padding:2em;text-align:center; color:red;'>Material ID is required</div>";
    exit;
}

// Validate material ID (alphanumeric and some special characters)
if (!preg_match('/^[A-Z0-9]+$/', $material_id)) {
    echo "<div style='padding:2em;text-align:center; color:red;'>Invalid material ID format</div>";
    exit;
}

// Create the material directory if it doesn't exist
$upload_dir = "material/$material_id/";
if (!is_dir($upload_dir)) {
    if (!mkdir($upload_dir, 0755, true)) {
        echo "<div style='padding:2em;text-align:center; color:red;'>Failed to create directory</div>";
        exit;
    }
}

// Check if files were uploaded
if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
    echo "<div style='padding:2em;text-align:center; color:red;'>No files were uploaded</div>";
    exit;
}

$uploaded_files = [];
$errors = [];

// Process each uploaded file
foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
    $file_name = $_FILES['images']['name'][$key];
    $file_size = $_FILES['images']['size'][$key];
    $file_error = $_FILES['images']['error'][$key];
    
    // Check for upload errors
    if ($file_error !== UPLOAD_ERR_OK) {
        $errors[] = "Error uploading $file_name: " . $file_error;
        continue;
    }
    
    // Validate file type
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif','application/pdf'];
    $file_type = mime_content_type($tmp_name);
    
    if (!in_array($file_type, $allowed_types)) {
        $errors[] = "Invalid file type for $file_name. Allowed types: JPG, PNG, GIF ,PDF";
        continue;
    }
    
    // Validate file extension (ป้องกัน spoof)
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_extensions)) {
        $errors[] = "Invalid file extension for $file_name. Allowed extensions: JPG, PNG, GIF, PDF";
        continue;
    }
    // Validate file size (max 10MB)
    $max_size = 10 * 1024 * 1024; // 10MB
    if ($file_size > $max_size) {
        $errors[] = "File $file_name is too large. Maximum size is 10MB";
        continue;
    }
    
    // Generate unique filename
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $destination = $upload_dir . $unique_filename;
    
    // Move uploaded file
    if (move_uploaded_file($tmp_name, $destination)) {
        $uploaded_files[] = $unique_filename;
    } else {
        $errors[] = "Failed to save $file_name";
    }
}

// Show result as HTML and redirect
if (count($uploaded_files) > 0) {
    // Success
    echo "<div style='padding:2em;text-align:center;'>
            <h2 style='color:green;'>อัปโหลดรูปภาพสำเร็จ!</h2>
            <p>กำลังกลับไปยังหน้าหลัก...</p>
          </div>
          <script>
            setTimeout(function() {
                window.location.href = 'index.php?id=" . htmlspecialchars($material_id) . "';
            }, 1500);
          </script>";
    exit;
} else {
    // Error
    echo "<div style='padding:2em;text-align:center;'>
            <h2 style='color:red;'>เกิดข้อผิดพลาดในการอัปโหลด</h2>
            <ul style='color:#c00;'>";
    foreach ($errors as $err) {
        echo "<li>" . htmlspecialchars($err) . "</li>";
    }
    echo "</ul>
          <a href='index.php?id=" . htmlspecialchars($material_id) . "'>กลับ</a>
          </div>";
    exit;
}
?> 