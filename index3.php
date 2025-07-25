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
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px dashed #2196F3;
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
            border-radius: 25px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(33, 150, 243, 0.3);
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
    // Get only the id parameter
    $id = $_GET['id'] ?? '';
    
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
    
    // Sample data - in a real application, this would come from a database
    // For now, we'll use the URL parameters if they exist, otherwise show sample data
    $material_data = [
        'id' => $id,
        'name' => $_GET['mat_des'] ?? 'LOCK NUT KM16',
        'group' => $_GET['mat_group'] ?? 'ACAC',
        'size' => $_GET['size'] ?? 'KM16',
        'requester' => $_GET['requis'] ?? 'Engineering Department',
        'pr' => $_GET['pr'] ?? 'PR-2024-001',
        'po' => $_GET['po'] ?? 'PO-2024-001',
        'plant' => $_GET['plant'] ?? 'Plant A',
        'vendors' => [
            [
                'name' => $_GET['name1'] ?? 'Zubb Steel Co., Ltd.',
                'price' => $_GET['price1'] ?? '฿1,250.00'
            ],
            [
                'name' => $_GET['name2'] ?? 'Metal Supply Co.',
                'price' => $_GET['price2'] ?? '฿1,300.00'
            ],
            [
                'name' => $_GET['name3'] ?? 'Steel Solutions Ltd.',
                'price' => $_GET['price3'] ?? '฿1,180.00'
            ],
            [
                'name' => $_GET['name4'] ?? 'Industrial Parts Co.',
                'price' => $_GET['price4'] ?? '฿1,350.00'
            ],
            [
                'name' => $_GET['name5'] ?? 'Quality Steel Corp.',
                'price' => $_GET['price5'] ?? '฿1,220.00'
            ]
        ],
        'selected_vendor' => $_GET['sname'] ?? 'Zubb Steel Co., Ltd.',
        'final_price' => $_GET['finalprice'] ?? '฿1,250.00'
    ];
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
                    $dirname = "material/$id/";
                    $images = glob($dirname."*.{JPG,jpg,jpeg,png,gif}", GLOB_BRACE);
                    
                    if (empty($images)) {
                        echo '<div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                No images available for this material. Please upload images below.
                              </div>';
                    } else {
                        foreach($images as $image) {
                            $filename = basename($image);
                            echo '<div class="image-item">
                                    <a href="'.$image.'" data-lightbox="material-gallery" data-title="'.$filename.'">
                                        <img src="'.$image.'" alt="'.$filename.'" class="img-fluid">
                                    </a>
                                  </div>';
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
                                <div class="form-text">You can select multiple images. Supported formats: JPG, PNG, GIF</div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-upload w-100">
                                <i class="fas fa-upload me-2"></i>Upload Images
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
                            <th><i class="fas fa-ruler me-2"></i>Size</th>
                            <td><?php echo htmlspecialchars($material_data['size']); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-user me-2"></i>Requester</th>
                            <td><?php echo htmlspecialchars($material_data['requester']); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-file-alt me-2"></i>PR Number</th>
                            <td><?php echo htmlspecialchars($material_data['pr']); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-shopping-cart me-2"></i>PO Number</th>
                            <td><?php echo htmlspecialchars($material_data['po']); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-industry me-2"></i>Plant</th>
                            <td><?php echo htmlspecialchars($material_data['plant']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Vendor Information -->
            <div class="info-table mt-4">
                <h3 class="p-4 mb-0 border-bottom"><i class="fas fa-store me-2"></i>Vendor Comparison</h3>
                <table class="table table-hover">
                    <tbody>
                        <?php foreach($material_data['vendors'] as $index => $vendor): ?>
                        <tr>
                            <th><i class="fas fa-building me-2"></i>Vendor <?php echo $index + 1; ?></th>
                            <td>
                                <div class="vendor-info">
                                    <strong><?php echo htmlspecialchars($vendor['name']); ?></strong>
                                    <span class="price-highlight float-end"><?php echo htmlspecialchars($vendor['price']); ?></span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-success">
                            <th><i class="fas fa-check-circle me-2"></i>Selected Vendor</th>
                            <td>
                                <div class="vendor-info">
                                    <strong><?php echo htmlspecialchars($material_data['selected_vendor']); ?></strong>
                                    <span class="price-highlight float-end"><?php echo htmlspecialchars($material_data['final_price']); ?></span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
    
    <script>
        // Auto-refresh page after successful upload
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
            submitBtn.disabled = true;
        });

        // Initialize lightbox
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': 'Image %1 of %2'
        });
    </script>
</body>
</html>
