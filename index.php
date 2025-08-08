<?php
// ตั้งค่าเพื่อแสดงข้อผิดพลาดทั้งหมดสำหรับการดีบัก
error_reporting(E_ALL);
ini_set('display_errors', 1);

// เรียกไฟล์เชื่อมต่อฐานข้อมูล
include "conn.php";

// ---- [แก้ไขจุดที่ 1] ----
// ตรวจสอบ "โหมดร่าง" (draft mode) ตั้งแต่ต้นไฟล์ เพื่อให้ตัวแปรพร้อมใช้งานเสมอ
$is_draft_mode = isset($_GET['draft']);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material Information - <?php echo htmlspecialchars($_GET['id'] ?? ''); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Lightbox CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            margin: 20px auto;
            max-width: 1200px;
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(45deg, #2196F3, #21CBF3);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 300;
        }
        
        .content-section {
            padding: 30px;
        }
        
        .image-gallery {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .image-item {
            display: inline-block;
            margin: 10px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .image-item:hover {
            transform: translateY(-5px);
        }
        
        .image-item img {
            width: 200px;
            height: 200px;
            object-fit: cover;
        }
        
        .upload-section {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 1.5rem; /* ปรับ padding ให้สวยงาม */
            margin-bottom: 1.5rem;
            border: 1.5px dashed #2196F3;
        }
        
        .upload-section h3 {
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }
        
        .info-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .info-table .table th {
            width: 200px;
        }

        .btn-upload {
            background: linear-gradient(45deg, #2196F3, #21CBF3);
            border: none;
            border-radius: 8px; /* ปรับรูปร่างปุ่ม */
            padding: 10px 15px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(33, 150, 243, 0.3);
        }

        @media (max-width: 768px) {
            .main-container { margin: 10px; border-radius: 15px; }
            .header h1 { font-size: 2rem; }
            .content-section { padding: 20px; }
            .image-item img { width: 150px; height: 150px; }
        }
    </style>
</head>
<body>

<?php
// ถ้ายังไม่มี id หรือ id เป็นค่าว่าง ให้แสดงหน้าค้นหา
if (!isset($_GET['id']) || $_GET['id'] === '') {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    echo '<div class="container mt-5">';
    echo '<div class="card p-4 shadow-lg">';
    echo '<h3 class="mb-3"><i class="fas fa-search me-2"></i>ค้นหา Material</h3>';
    echo '<form method="GET" action="index.php" class="d-flex mb-3">';
    echo '<input type="text" name="search" class="form-control me-2" placeholder="ชื่อหรือรหัสวัสดุ" value="'.htmlspecialchars($search).'" autofocus>';
    echo '<button type="submit" class="btn btn-primary flex-shrink-0"><i class="fas fa-search"></i> ค้นหา</button>';
    echo '</form>';

    if ($search !== '') {
        $sql = "SELECT TOP 20 MATNR, MAKTX FROM vw_picsap WHERE MATNR LIKE ? OR MAKTX LIKE ?";
        $params = array('%'.$search.'%', '%'.$search.'%');
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            echo '<div class="alert alert-danger">เกิดข้อผิดพลาดในการค้นหา</div>';
        } else {
            echo '<ul class="list-group">';
            $found = false;
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $found = true;
                echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                echo htmlspecialchars($row['MATNR']).' - '.htmlspecialchars($row['MAKTX']);
                // ---- [สำคัญ] ----
                // ลิงก์ไปยังหน้ารายละเอียด ต้องมี &draft= เพื่อเข้าสู่โหมดแก้ไข/อัปโหลด
                echo '<a href="index.php?id='.urlencode($row['MATNR']).'&draft=" class="btn btn-sm btn-success">ดูรายละเอียด/แก้ไข</a>';
                echo '</li>';
            }
            if (!$found) {
                echo '<li class="list-group-item text-danger">ไม่พบข้อมูลวัสดุที่ค้นหา</li>';
            }
            echo '</ul>';
            sqlsrv_free_stmt($stmt);
        }
    }
    echo '</div></div>';
    sqlsrv_close($conn);
    exit; // จบการทำงานสำหรับหน้าค้นหา
}

// ---- เริ่มส่วนของหน้ารายละเอียด ----
$id = $_GET['id'];

$sql = "SELECT * FROM vw_picsap WHERE MATNR = ?";
$params = array($id);
$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    die('SQL ERROR: ' . print_r(sqlsrv_errors(), true));
}
$show = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$show) {
    echo '<div class="container mt-5"><div class="alert alert-warning" role="alert"><h4 class="alert-heading">ไม่พบข้อมูล!</h4><p>ไม่พบข้อมูลรหัสวัสดุนี้ในฐานข้อมูล</p></div></div>';
    exit;
}

// จัดเตรียมข้อมูลลงใน array เพื่อให้เรียกใช้ง่าย
$material_data = [
    'id'      => $show['MATNR'] ?? '',
    'name'    => $show['MAKTX'] ?? '',
    'group'   => isset($show['MATNR']) ? substr($show['MATNR'], 0, 4) : '',
    'size'    => $show['EXTWG'] ?? '',
    'uom'     => ($show['MEINS'] ?? '') === 'ST' ? 'PC' : ($show['MEINS'] ?? ''),
];
?>

    <div class="main-container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-cube me-3"></i>Material Information System</h1>
            <p class="mb-0">Material Code: <strong><?php echo htmlspecialchars($material_data['id']); ?></strong></p>
        </div>

        <div class="content-section">
            <!-- Image Gallery -->
            <div class="image-gallery">
                <h3 class="mb-4"><i class="fas fa-images me-2"></i>Material Images</h3>
                <div class="text-center">
                    <?php
                    $dirname = "material/" . $material_data['id'] . "/";
                    $images = glob($dirname."*.{JPG,jpg,jpeg,png,gif,pdf}", GLOB_BRACE);
                    
                    if (empty($images)) {
                        echo '<div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>ยังไม่มีรูปภาพสำหรับ Material นี้</div>';
                    } else {
                        foreach($images as $image) {
                            $filename = basename($image);
                            $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                            echo '<div class="image-item text-center">';
                            if ($file_extension === 'pdf') {
                                echo '<a href="'.$image.'" download class="d-block mb-2" style="font-size:3em;color:#e53935;"><i class="fas fa-file-pdf"></i></a>';
                                echo '<div class="mb-2" style="font-size:0.95em; word-wrap: break-word;">'.htmlspecialchars($filename).'</div>';
                            } else {
                                echo '<a href="'.$image.'" data-lightbox="material-gallery" data-title="'.htmlspecialchars($filename).'">';
                                echo '<img src="'.$image.'" alt="'.htmlspecialchars($filename).'" class="img-fluid">';
                                echo '</a>';
                            }

                            // ---- [แก้ไขจุดที่ 2] ----
                            // แสดงปุ่มลบก็ต่อเมื่ออยู่ใน "โหมดร่าง" ($is_draft_mode เป็น true)
                            if ($is_draft_mode) {
                                echo '<form action="delete_image.php" method="POST" style="margin-top:8px;">';
                                echo '<input type="hidden" name="material_id" value="'.htmlspecialchars($material_data['id']).'">';
                                echo '<input type="hidden" name="filename" value="'.htmlspecialchars($filename).'">';
                                echo '<input type="hidden" name="draft" value="">'; // ส่งสถานะ draft ไปด้วย
                                echo '<button type="button" class="btn btn-danger btn-sm btn-delete-image"><i class="fas fa-trash-alt"></i> ลบ</button>';
                                echo '</form>';
                            }
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>

            <!-- ---- [แก้ไขจุดที่ 3] ---- -->
            <!-- แสดงส่วนอัปโหลดก็ต่อเมื่ออยู่ใน "โหมดร่าง" ($is_draft_mode เป็น true) -->
            <?php if ($is_draft_mode): ?>
            <div class="upload-section">
                <h3 class="mb-4"><i class="fas fa-cloud-upload-alt me-2"></i>Upload Images / PDF</h3>
                <form action="upload_handler.php" method="post" enctype="multipart/form-data" id="uploadForm">
                    <input type="hidden" name="material_id" value="<?php echo htmlspecialchars($material_data['id']); ?>">
                    <input type="hidden" name="draft" value=""> <!-- ส่งสถานะ draft ไปด้วย -->
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label for="images" class="form-label">เลือกไฟล์ (รูปภาพ หรือ PDF)</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*,application/pdf" required>
                            <div class="form-text">รองรับ: JPG, PNG, GIF, PDF (ขนาดไม่เกิน 10MB ต่อไฟล์)</div>
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <button type="submit" class="btn btn-upload w-100"><i class="fas fa-upload me-2"></i>Upload File</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- Material Information Table -->
            <div class="info-table mt-4">
                <h3 class="p-4 mb-0 border-bottom"><i class="fas fa-info-circle me-2"></i>Material Details</h3>
                <table class="table table-hover mb-0">
                    <tbody>
                        <tr><th><i class="fas fa-barcode me-2"></i>Material Code</th><td><strong><?php echo htmlspecialchars($material_data['id']); ?></strong></td></tr>
                        <tr><th><i class="fas fa-tag me-2"></i>Material Name</th><td><?php echo htmlspecialchars($material_data['name']); ?></td></tr>
                        <tr><th><i class="fas fa-layer-group me-2"></i>Material Group</th><td><?php echo htmlspecialchars($material_data['group']); ?></td></tr>
                        <tr><th><i class="fas fa-ruler me-2"></i>Material Catalog</th><td><?php echo htmlspecialchars($material_data['size']); ?></td></tr>
                        <tr><th><i class="fas fa-box me-2"></i>UOM</th><td><?php echo htmlspecialchars($material_data['uom']); ?></td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-light"><i class="fas fa-search me-2"></i>กลับไปหน้าค้นหา</a>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>หากมีข้อผิดพลาดหรือข้อสงสัย กรุณาติดต่อแผนกไอที</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
    
    <!-- Delete Image Modal -->
    <div class="modal fade" id="deleteImageModal" tabindex="-1" aria-labelledby="deleteImageModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white"><h5 class="modal-title" id="deleteImageModalLabel"><i class="fas fa-trash-alt me-2"></i>ยืนยันการลบ</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button></div>
          <div class="modal-body text-center"><p class="fs-5">คุณต้องการลบไฟล์นี้ใช่หรือไม่?</p></div>
          <div class="modal-footer justify-content-center border-0"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button><button type="button" class="btn btn-danger" id="confirmDeleteBtn">ยืนยันลบ</button></div>
        </div>
      </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Upload form logic ---
        const uploadForm = document.getElementById('uploadForm');
        if(uploadForm) {
            uploadForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Uploading...';
                submitBtn.disabled = true;
            });
        }

        // --- Lightbox initialization ---
        try {
            if (typeof lightbox !== 'undefined') {
                lightbox.option({ 'resizeDuration': 200, 'wrapAround': true, 'albumLabel': 'Image %1 of %2' });
            }
        } catch (e) {
            console.error("Lightbox could not be initialized.", e);
        }

        // --- Delete modal logic ---
        let formToDelete = null;
        const deleteModalEl = document.getElementById('deleteImageModal');
        if (deleteModalEl) {
            const bsModal = new bootstrap.Modal(deleteModalEl);
            document.querySelectorAll('.btn-delete-image').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    formToDelete = btn.closest('form');
                    bsModal.show();
                });
            });
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (formToDelete) {
                    formToDelete.submit();
                }
            });
        }
    });
    </script>

    <!-- Display message from URL (e.g., after upload/delete) -->
    <?php
    if (isset($_GET['msg']) && $_GET['msg'] !== '') {
        $msg = htmlspecialchars($_GET['msg']);
        echo '<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1056">';
        echo '<div id="toastMsg" class="toast show align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">';
        echo '<div class="d-flex"><div class="toast-body">' . $msg . '</div>';
        echo '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div></div></div>';
    }

    // ปิดการเชื่อมต่อฐานข้อมูลเมื่อจบการทำงาน
    sqlsrv_close($conn);
    ?>
</body>
</html>