
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

// ---- ส่วนที่แก้ไข (จุดที่ 1) ----
// สร้าง URL พื้นฐานสำหรับ redirect โดยตรวจสอบโหมด draft ไปด้วยเลย
$base_redirect_url = 'index.php?id=' . htmlspecialchars($material_id);
if (isset($_POST['draft'])) {
    // ถ้ามีการส่งค่า draft มาด้วย ให้ต่อท้าย &draft= กลับไป
    $base_redirect_url .= '&draft=';
}
// ---- จบส่วนที่แก้ไข (จุดที่ 1) ----


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
    $redirect_with_msg = $base_redirect_url . '&msg=' . urlencode('No files were uploaded');
    header('Location: ' . $redirect_with_msg);
    exit;
}

$uploaded_files = [];
$errors = [];

// Process each uploaded file
foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
    // ... (โค้ดส่วนประมวลผลไฟล์ยังคงเหมือนเดิมทุกประการ)
    $file_name = $_FILES['images']['name'][$key];
    $file_size = $_FILES['images']['size'][$key];
    $file_error = $_FILES['images']['error'][$key];
    
    if ($file_error !== UPLOAD_ERR_OK) {
        // ...
        continue;
    }
    
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif','application/pdf'];
    $file_type = mime_content_type($tmp_name);
    
    if (!in_array($file_type, $allowed_types)) {
        $errors[] = "Invalid file type for $file_name. Allowed types: JPG, PNG, GIF ,PDF";
        continue;
    }
    
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_extensions)) {
        $errors[] = "Invalid file extension for $file_name. Allowed extensions: JPG, PNG, GIF, PDF";
        continue;
    }

    $max_size = 10 * 1024 * 1024;
    if ($file_size > $max_size) {
        $errors[] = "File $file_name is too large. Maximum size is 10MB";
        continue;
    }
    
    // ใช้ชื่อไฟล์เดิม แต่ clean ชื่อไฟล์เพื่อความปลอดภัย
    $safe_filename = preg_replace("/[^a-zA-Z0-9-_\.]/", "", basename($file_name));
    $destination = $upload_dir . $safe_filename;
    
    if (move_uploaded_file($tmp_name, $destination)) {
        $uploaded_files[] = $safe_filename;
    } else {
        $errors[] = "Failed to save $file_name";
    }
}

// Show result as HTML and redirect
if (count($uploaded_files) > 0 && count($errors) == 0) {
    // ---- ส่วนที่แก้ไข (จุดที่ 2) ----
    // Success - เปลี่ยนไปใช้ PHP header redirect เพื่อความรวดเร็วและแน่นอน
    $success_msg = "อัปโหลดสำเร็จ " . count($uploaded_files) . " ไฟล์";
    $redirect_with_msg = $base_redirect_url . '&msg=' . urlencode($success_msg);
    header('Location: ' . $redirect_with_msg);
    exit;

} else {
    // ---- ส่วนที่แก้ไข (จุดที่ 3) ----
    // Error display with improved styling and corrected redirect link
    echo "<div style='max-width:500px; margin:2em auto; padding:2em; border:1px solid #f5c2c7; background:#fff0f3; border-radius:10px; text-align:center; box-shadow:0 2px 8px rgba(220,53,69,0.08);'>
            <h2 style='color:#dc3545; margin-bottom:1em;'><span style='font-size:1.5em;'>&#9888;</span> อัปโหลดไม่สำเร็จ</h2>
            <p>อัปโหลดสำเร็จ: " . count($uploaded_files) . " ไฟล์ | ข้อผิดพลาด: " . count($errors) . " ไฟล์</p>
            <ul style='color:#b02a37; text-align:left; display:inline-block; margin-bottom:1em;'>";
    foreach ($errors as $err) {
        echo "<li>" . htmlspecialchars($err) . "</li>";
    }
    echo "</ul>
          <!-- ใช้ตัวแปร $base_redirect_url ที่สร้างไว้ -->
          <a href='" . $base_redirect_url . "' style='display:inline-block; margin-top:1em; padding:0.5em 1.5em; background:#dc3545; color:#fff; border-radius:5px; text-decoration:none; font-weight:bold; transition:background 0.2s;'>กลับ</a>
          </div>";

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
}
?>