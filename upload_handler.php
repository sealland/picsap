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
    // Error display with improved styling
    echo "<div style='max-width:500px; margin:2em auto; padding:2em; border:1px solid #f5c2c7; background:#fff0f3; border-radius:10px; text-align:center; box-shadow:0 2px 8px rgba(220,53,69,0.08);'>
            <h2 style='color:#dc3545; margin-bottom:1em;'><span style='font-size:1.5em;'>&#9888;</span> อัปโหลดไม่สำเร็จ</h2>
            <ul style='color:#b02a37; text-align:left; display:inline-block; margin-bottom:1em;'>";
    foreach ($errors as $err) {
        echo "<li>" . htmlspecialchars($err) . "</li>";
    }
    echo "</ul>
          <a href='index.php?id=" . htmlspecialchars($material_id) . "' style='display:inline-block; margin-top:1em; padding:0.5em 1.5em; background:#dc3545; color:#fff; border-radius:5px; text-decoration:none; font-weight:bold; transition:background 0.2s;'>กลับ</a>
          </div>";

    // PDF-specific error note
    if (!empty($errors)) {
        foreach ($errors as $err) {
            if (
                (strpos($err, 'Invalid file type') !== false && strpos(strtolower($err), 'pdf') !== false) ||
                (strpos($err, 'Invalid file extension') !== false && strpos(strtolower($err), 'pdf') !== false)
            ) {
                echo "<div style='max-width:500px; margin:1em auto; padding:1em 2em; background:#fff3cd; color:#856404; border:1px solid #ffeeba; border-radius:8px; font-size:0.97em;'>
                    <strong>หมายเหตุ:</strong> หากไฟล์ PDF ของคุณไม่สามารถอัปโหลดได้ อาจเกิดจากสาเหตุต่อไปนี้:
                    <ul style='margin:0.5em 0 0 1.5em; color:#856404; text-align:left;'>
                        <li>ไฟล์ PDF ถูกสร้างด้วยวิธีที่ไม่รองรับ หรือมีโครงสร้างไฟล์ผิดปกติ</li>
                        <li>ไฟล์ PDF มีการเข้ารหัสหรือป้องกันรหัสผ่าน</li>
                        <li>ไฟล์ PDF มีขนาดใหญ่เกิน 10MB</li>
                        <li>ไฟล์ PDF มีนามสกุลไม่ถูกต้อง หรือมีการเปลี่ยนชื่อไฟล์โดยไม่ถูกต้อง</li>
                        <li>เบราว์เซอร์หรืออุปกรณ์ที่ใช้งานไม่รองรับการอัปโหลดไฟล์ PDF บางประเภท</li>
                    </ul>
                    หากยังพบปัญหา กรุณาตรวจสอบไฟล์ PDF หรือแจ้งผู้ดูแลระบบ
                </div>";
                break;
            }
        }
    }

    // Show PHP upload error details for debugging (optional, can be commented out in production)
    foreach ($_FILES['images']['error'] as $key => $error_code) {
        if ($error_code !== UPLOAD_ERR_OK) {
            $file_name = $_FILES['images']['name'][$key];
            switch ($error_code) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $error_message = "ไฟล์ $file_name มีขนาดใหญ่เกินกำหนด (10MB)";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $error_message = "ไฟล์ $file_name ถูกอัปโหลดเพียงบางส่วน";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error_message = "ไม่ได้เลือกไฟล์สำหรับ $file_name";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $error_message = "ไม่มีโฟลเดอร์ชั่วคราวสำหรับ $file_name";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $error_message = "ไม่สามารถบันทึก $file_name ลงดิสก์ได้";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $error_message = "PHP extension หยุดการอัปโหลดไฟล์ $file_name";
                    break;
                default:
                    $error_message = "เกิดข้อผิดพลาดไม่ทราบสาเหตุสำหรับ $file_name (code $error_code)";
            }
            echo "<div style='max-width:500px; margin:1em auto; padding:0.7em 1.5em; background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:7px; font-size:0.97em;'>$error_message</div>";
        }
    }
    // Debugging: Show actual file size and PHP limits
    // $max_upload = ini_get('upload_max_filesize');
    // $max_post = ini_get('post_max_size');
    // $memory_limit = ini_get('memory_limit');
    // echo "<div style='color:#888; margin-top:1em; font-size:0.95em;'>
    //         <strong>Debug info:</strong><br>
    //         Actual file size: " . number_format($file_size) . " bytes<br>
    //         upload_max_filesize: $max_upload<br>
    //         post_max_size: $max_post<br>
    //         memory_limit: $memory_limit
    //       </div>";
    // exit;
}
?> 