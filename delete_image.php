<?php
// delete_image.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $material_id = isset($_POST['material_id']) ? $_POST['material_id'] : '';
    $filename = isset($_POST['filename']) ? $_POST['filename'] : '';
    $error = '';
    $success = '';

    // Validate input
    if (empty($material_id) || empty($filename)) {
        $error = 'ข้อมูลไม่ครบถ้วน';
    } else {
        $dir = __DIR__ . "/material/" . basename($material_id) . "/";
        $file = $dir . basename($filename);
        $debug = "<br>DEBUG: file path: $file ";
        $debug .= "<br>file_exists: ".(file_exists($file)?'YES':'NO');
        $debug .= " | is_file: ".(is_file($file)?'YES':'NO');
        if (file_exists($file) && is_file($file)) {
            if (unlink($file)) {
                $success = 'ลบรูปภาพสำเร็จ';
            } else {
                $last_error = error_get_last();
                $error = 'ไม่สามารถลบไฟล์ได้';
                $debug .= "<br>unlink error: ".print_r($last_error, true);
            }
        } else {
            $error = 'ไม่พบไฟล์ที่ต้องการลบ';
        }
    }
    // Logging
    $logfile = __DIR__ . '/log_delete_image.txt';
    $datetime = date('Y-m-d H:i:s');
    $user_ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $computer_name = $_SERVER['COMPUTERNAME'] ?? gethostbyaddr($user_ip);
    $log_msg = "[$datetime] material_id=$material_id, filename=$filename, computer_name=$computer_name, ip=$user_ip, result=";
    if ($success) {
        $log_msg .= "SUCCESS\n";
    } else {
        $log_msg .= "FAIL: $error\n";
    }
    file_put_contents($logfile, $log_msg, FILE_APPEND | LOCK_EX);

    // ---- ส่วนที่แก้ไข ----
    // สร้าง URL พื้นฐานสำหรับ redirect
    $redirect_url = 'index.php?id=' . urlencode($material_id);

    // ตรวจสอบว่ามีการส่งค่า 'draft' มาด้วยหรือไม่ (จากการเพิ่ม input ใน index.html)
    if (isset($_POST['draft'])) {
        // ถ้ามี ให้ต่อพารามิเตอร์ &draft= เข้าไปใน URL
        $redirect_url .= '&draft=';
    }

    // เพิ่มข้อความสถานะ (success/error)
    if ($error) {
        $redirect_url .= '&msg=' . urlencode($error . $debug);
    } elseif ($success) {
        $redirect_url .= '&msg=' . urlencode($success);
    }

    // สั่ง redirect ไปยัง URL ที่สร้างขึ้น
    header('Location: ' . $redirect_url);
    exit;
    // ---- จบส่วนที่แก้ไข ----

} else {
    header('Location: index.php');
    exit;
}
?>