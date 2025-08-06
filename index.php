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
            padding: 6px 8px;
            margin-bottom: 12px;
            border: 1.5px dashed #2196F3;
            font-size: 0.88rem;
        }
        
        .upload-section h3 {
            margin-bottom: 8px;
            font-size: 1rem;
        }
        
        .upload-section .form-label,
        .upload-section .form-text {
            font-size: 0.88rem;
        }
        
        .info-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .info-table .table {
            margin: 0;
        }
        
        .info-table .table th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #495057;
            width: 200px;
        }
        
        .info-table .table td {
            border: none;
            border-bottom: 1px solid #dee2e6;
            padding: 15px;
        }
        
        .btn-upload {
            background: linear-gradient(45deg, #2196F3, #21CBF3);
            border: none;
            border-radius: 18px;
            padding: 5px 12px;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(33, 150, 243, 0.3);
        }
        
        .upload-section input[type="file"] {
            font-size: 0.88rem;
            padding: 3px 0;
            height: 28px;
        }
        
        .no-data {
            color: #dc3545;
            font-weight: 600;
        }
        
        .price-highlight {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        
        .vendor-info {
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 10px;
            margin: 5px 0;
        }
        
        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .content-section {
                padding: 20px;
            }
            
            .image-item img {
                width: 150px;
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ?>

    <?php
    include "conn.php";

    // ถ้ายังไม่มี id ให้แสดงฟอร์มค้นหา
    if (!isset($_GET['id']) || $_GET['id'] === '') {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        echo '<div class="container mt-5">';
        echo '<div class="card p-4">';
        echo '<h3 class="mb-3"><i class="fas fa-search me-2"></i>ค้นหา Material</h3>';
        echo '<form method="GET" action="index.php" class="d-flex mb-3">';
        echo '<input type="text" name="search" class="form-control me-2" placeholder="ชื่อหรือรหัสวัสดุ" value="'.htmlspecialchars($search).'">';
        echo '<button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> ค้นหา</button>';
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
                    echo '<a href="index.php?id='.urlencode($row['MATNR']).'&draft=" class="btn btn-sm btn-success">ดูรายละเอียด</a>';
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
        exit;
    }

    // Get only the id parameter
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    if (empty($id)) {
        echo '<div class="container mt-5">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Error!</h4>
                    <p>Material ID is required. Please provide an ID parameter.</p>
                    <hr>
                    <p class="mb-0">Example: index.php?id=ACAC00032</p>
                </div>
              </div>';
        exit;
    }

    $sql = "SELECT * FROM vw_picsap WHERE MATNR = ?";
    $params = array($id);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        echo 'SQL ERROR: ';
        die(print_r(sqlsrv_errors(), true));
    }
    $show = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    // ถ้าไม่พบข้อมูล
    if (!$show) {
        echo '<div class="container mt-5">
                <div class="alert alert-warning" role="alert">
                    <h4 class="alert-heading">ไม่พบข้อมูล!</h4>
                    <p>ไม่พบข้อมูลรหัสวัสดุนี้ในฐานข้อมูล</p>
                </div>
              </div>';
        exit;
    }

    $material_data = array(
        'id' => isset($show['MATNR']) ? $show['MATNR'] : '',
        'name' => isset($show['MAKTX']) ? $show['MAKTX'] : '',
        'group' => isset($show['MATNR']) ? substr($show['MATNR'],0,4) : '',
        'size' => isset($show['EXTWG']) ? $show['EXTWG'] : '',
        'requester' => (isset($show['MEINS']) && $show['MEINS'] === 'ST') ? 'PC' : (isset($show['MEINS']) ? $show['MEINS'] : ''),
        'pr' => isset($show['PR']) ? $show['PR'] : '',
        'po' => isset($show['PO']) ? $show['PO'] : '',
        'plant' => isset($show['DEPARTMENT']) ? $show['DEPARTMENT'] : '',
        'vendors' => array(
            array('name' => isset($show['VENDOR_1']) ? $show['VENDOR_1'] : '', 'price' => isset($show['DETAIL_1']) ? $show['DETAIL_1'] : ''),
            array('name' => isset($show['VENDOR_2']) ? $show['VENDOR_2'] : '', 'price' => isset($show['DETAIL_2']) ? $show['DETAIL_2'] : ''),
            array('name' => isset($show['VENDOR_3']) ? $show['VENDOR_3'] : '', 'price' => isset($show['DETAIL_3']) ? $show['DETAIL_3'] : ''),
            array('name' => isset($show['VENDOR_4']) ? $show['VENDOR_4'] : '', 'price' => isset($show['DETAIL_4']) ? $show['DETAIL_4'] : ''),
            array('name' => isset($show['VENDOR_5']) ? $show['VENDOR_5'] : '', 'price' => isset($show['DETAIL_5']) ? $show['DETAIL_5'] : '')
        ),
        'selected_vendor' => isset($show['VENDOR_SELECT']) ? $show['VENDOR_SELECT'] : '',
        'final_price' => isset($show['FINAL_PRICE']) ? $show['FINAL_PRICE'] : ''
    );
    ?>

    <div class="main-container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-cube me-3"></i>Material Information System</h1>
            <p class="mb-0">Material Code: <strong><?php echo htmlspecialchars($id); ?></strong></p>
        </div>

        <div class="content-section">
            <!-- Image Gallery -->
            <div class="image-gallery">
                <h3 class="mb-4"><i class="fas fa-images me-2"></i>Material Images</h3>
                <div class="text-center">
                    <?php
                    $showDeleteBtn = (isset($_GET['draft']) && $_GET['draft'] === '');
                    $dirname = "material/$id/";
                    $images = glob($dirname."*.{JPG,jpg,jpeg,png,gif,pdf}", GLOB_BRACE);
                    
                    if (empty($images)) {
                        echo '<div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                No images available for this material. Please upload images below.
                            </div>';
                    } else {
                        foreach($images as $image) {
                            $filename = basename($image);
                            $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                            echo '<div class="image-item text-center">';
                            if ($file_extension === 'pdf') {
                                // แสดงไอคอน PDF และปุ่มดาวน์โหลด
                                echo '
                                    <a href="'.$image.'" download class="d-block mb-2" style="font-size:3em;color:#e53935;">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <div class="mb-2" style="font-size:0.95em;">'.$filename.'</div>
                                    <a href="'.$image.'" download class="btn btn-outline-danger btn-sm mb-2">
                                        <i class="fas fa-download"></i> ดาวน์โหลด PDF
                                    </a>
                                ';
                            } else {
                                // แสดงรูปภาพปกติ
                                echo '
                                    <a href="'.$image.'" data-lightbox="material-gallery" data-title="'.$filename.'">
                                        <img src="'.$image.'" alt="'.$filename.'" class="img-fluid">
                                    </a>
                                ';
                            }
                            // ปุ่มลบ (เหมือนเดิม)
                                if ($showDeleteBtn) {
                                    echo '
                                        <form action="delete_image.php" method="POST" style="margin-top:8px;">
                                            <input type="hidden" name="material_id" value="'.htmlspecialchars($id).'">
                                            <input type="hidden" name="filename" value="'.htmlspecialchars($filename).'">
                                            <button type="button" class="btn btn-danger btn-sm btn-delete-image">
                                                <i class="fas fa-trash-alt"></i> ลบรูป
                                            </button>
                                        </form>
                                    ';
                                }
                                echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>

            <!-- Upload Section -->
            <div class="upload-section">
                <h3 class="mb-4"><i class="fas fa-cloud-upload-alt me-2"></i>Upload Images</h3>
                <form action="upload_handler.php" method="post" enctype="multipart/form-data" id="uploadForm">
                    <input type="hidden" name="material_id" value="<?php echo htmlspecialchars($id); ?>">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="images" class="form-label">Select Images</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" required>
                                <div class="form-text">คุณสามารถเลือกอัพโหลดได้ครั้งละหลายไฟล์แต่ต้องไม่เกิน 10MB/File. Supported formats: JPG, PNG, GIF ,PDF</div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-upload w-100">
                                <i class="fas fa-upload me-2"></i>Upload File
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Material Information -->
            <div class="info-table">
                <h3 class="p-4 mb-0 border-bottom"><i class="fas fa-info-circle me-2"></i>Material Details</h3>
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th><i class="fas fa-barcode me-2"></i>Material Code</th>
                            <td><strong><?php echo htmlspecialchars($material_data['id']); ?></strong></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-tag me-2"></i>Material Name</th>
                            <td><?php echo htmlspecialchars($material_data['name']); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-layer-group me-2"></i>Material Group</th>
                            <td><?php echo htmlspecialchars($material_data['group']); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-ruler me-2"></i>Material Catalog</th>
                            <td><?php echo htmlspecialchars($material_data['size']); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-user me-2"></i>UOM</th>
                            <td><?php echo htmlspecialchars($material_data['requester']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Search Section -->
            <div class="search-section mb-4">
                <form method="GET" action="index.php" class="d-flex">
                    <input type="text" name="search" placeholder="ค้นหา material" class="form-control me-2" aria-label="Search">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> ค้นหา
                    </button>
                </form>
            </div>

            <!-- Search Results -->
            <div class="search-results">
                <?php
                // เช็คว่ามีการค้นหาหรือไม่
                $search = isset($_GET['search']) ? $_GET['search'] : '';

                // ตัวอย่างการดึงข้อมูล material จากฐานข้อมูล
                // สมมติใช้ MySQL และมีตารางชื่อ material
                $conn = new mysqli('localhost', 'username', 'password', 'database');
                $sql = "SELECT * FROM material";
                if ($search != '') {
                    $sql .= " WHERE name LIKE '%" . $conn->real_escape_string($search) . "%'";
                }
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<ul class='list-group'>";
                    while($row = $result->fetch_assoc()) {
                        echo "<li class='list-group-item'>" . htmlspecialchars($row['name']) . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<div class='alert alert-warning' role='alert'>ไม่พบ material</div>";
                }
                $conn->close();
                ?>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4">
                <div class="alert alert-info">
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
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="deleteImageModalLabel"><i class="fas fa-trash-alt me-2"></i>ยืนยันการลบรูปภาพ</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <p>คุณต้องการลบรูปภาพนี้ใช่หรือไม่?</p>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">ยืนยันลบ</button>
          </div>
        </div>
      </div>
    </div>
    <script>
        // Auto-refresh page after successful upload
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
            submitBtn.disabled = true;
        });

        // Initialize lightbox (ป้องกัน error ถ้า lightbox ไม่โหลด)
        try {
            if (typeof lightbox !== 'undefined') {
                lightbox.option({
                    'resizeDuration': 200,
                    'wrapAround': true,
                    'albumLabel': 'Image %1 of %2'
                });
            }
        } catch (e) {
            // lightbox error, do nothing
        }

        // Delete image modal logic (ทำงานได้แม้ lightbox error)
        let formToDelete = null;
        document.querySelectorAll('.btn-delete-image').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                formToDelete = btn.closest('form');
                const modal = new bootstrap.Modal(document.getElementById('deleteImageModal'));
                modal.show();
            });
        });
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (formToDelete) {
                formToDelete.submit();
            }
        });
    </script>

    <!-- Show alert message if msg is set -->
    <?php
    if (isset($_GET['msg']) && $_GET['msg'] !== '') {
        $msg = htmlspecialchars($_GET['msg']);
        echo '<div class="container mt-3"><div class="alert alert-info text-center">'. $msg .'</div></div>';
    }
    ?>

    <?php
    include 'conn.php';

    // รับ parameter id หรือ search
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // สร้าง query สำหรับค้นหา
    $sql = "SELECT * FROM material";
    $params = array();

    if ($id != '') {
        $sql .= " WHERE id = ?";
        $params[] = $id;
    } elseif ($search != '') {
        $sql .= " WHERE name LIKE ?";
        $params[] = '%' . $search . '%';
    }

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    echo '<form method="GET" action="index.php">
            <input type="text" name="search" placeholder="ค้นหา material" value="' . htmlspecialchars($search) . '">
            <button type="submit">ค้นหา</button>
          </form>';

    echo "<ul>";
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo "<li>" . htmlspecialchars($row['name']) . "</li>";
    }
    echo "</ul>";

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    ?>
</body>
</html>