<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem bài thi tự luận</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .sidebar {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        .content {
            padding: 20px;
        }
        .menu-item {
            padding: 10px 0;
        }
        .exam-header {
            background-color: #f1f8ff;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            margin-bottom: 20px;
        }
        .exam-content {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .menu-item {
    padding: 12px 20px;
    margin: 8px 0;
    background-color: #f0f0f0;
    border-radius: 8px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.menu-item a {
    text-decoration: none;
    color: #333;
    font-weight: bold;
    font-size: 16px;
    display: block;
}

.menu-item:hover {
    background-color: #007bff;
    transform: translateX(5px);
}

.menu-item:hover a {
    color: #fff;
}

    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h3>Quản lý bài thi</h3>
                <div class="menu-item">
                    <a href="index.php?controller=trangchu">Trang tổng quan</a>
                </div>
                <div class="menu-item">
                    <a href="index.php?controller=sinhvien">Quản lý sinh viên</a>
                </div>
                <div class="menu-item">
                    <a href="index.php?controller=cauhoi">Quản lý câu hỏi</a>
                </div>
                <div class="menu-item">
                    <a href="index.php?controller=baithi">Quản lý bài thi</a>
                </div>
                <div class="menu-item">
                    <a href="index.php?controller=lophoc">Quản lý lớp học</a>
                </div>
                <div class="menu-item">     
                    <a href="index.php?controller=ketquabaithi">Quản lý kết quả bài thi</a>
                </div>
                <div class="menu-item">
                    <a href="index.php?controller=auth&action=logout" class="btn btn-danger w-100">Đăng xuất</a>
                </div>
            </div>
            
            <!-- Main content -->
            <div class="col-md-9 content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Xem bài thi tự luận</h2>
                    <div>
                        <a href="index.php?controller=baithituluan&action=edit&id=<?php echo $baiThi['id']; ?>" class="btn btn-warning me-2">
                            <i class="bi bi-pencil-square"></i> Sửa bài thi
                        </a>
                        <a href="index.php?controller=baithi" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                
                <div class="exam-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="mb-2"><?php echo htmlspecialchars($baiThi['tieu_de']); ?></h3>
                            <p class="text-muted mb-0">
                                <?php 
                                if ($baiThi['thoi_gian_lam']) {
                                    echo "Thời gian làm bài: " . $baiThi['thoi_gian_lam'] . " phút";
                                } else {
                                    echo "Thời gian mở: " . date('d/m/Y H:i', strtotime($baiThi['thoi_gian_bat_dau']));
                                    if ($baiThi['thoi_gian_ket_thuc']) {
                                        echo " - " . date('d/m/Y H:i', strtotime($baiThi['thoi_gian_ket_thuc']));
                                    }
                                }
                                ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if (isset($baiThi['so_lan_lam'])): ?>
                            <p class="mb-1">Số lần được làm: <?php echo $baiThi['so_lan_lam']; ?></p>
                            <?php endif; ?>
                            <p class="mb-0">Ngày tạo: <?php echo date('d/m/Y H:i', strtotime($baiThi['ngay_tao'])); ?></p>
                        </div>
                    </div>
                    <div class="card mb-4">
                    <div class="card-header  text-Black">
                        <h5 class="card-title mb-0">Thông tin lớp học và môn học</h5>
                    </div>
                    <div class="card-body">
                        <?php 
                            // Lấy thông tin lớp học
                            $lopHoc = $this->giaoVienModel->getLopHocById($baiThi['lop_hoc_id']);
                            
                            // Lấy thông tin môn học
                            require_once 'Model/MonHocModel.php';
                            $monHocModel = new MonHocModel();
                            $monHoc = $monHocModel->getMonHocById($baiThi['mon_hoc_id']);
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-users me-3 fs-4 text-primary"></i>
                                    <div>
                                        <h6 class="mb-1">Lớp học</h6>
                                        <p class="mb-0"><?php echo htmlspecialchars($lopHoc['ten_lop']); ?> (<?php echo htmlspecialchars($lopHoc['ma_lop']); ?>)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-book me-3 fs-4 text-primary"></i>
                                    <div>
                                        <h6 class="mb-1">Môn học</h6>
                                        <p class="mb-0"><?php echo htmlspecialchars($monHoc['ten_mon']); ?> (<?php echo htmlspecialchars($monHoc['ma_mon']); ?>)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                
               
                
                <?php if (!empty($baiThi['mo_ta'])): ?>
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Mô tả</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($baiThi['mo_ta'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Nội dung bài thi</h5>
                    </div>
                    <div class="card-body">
                        <div class="exam-content">
                            <?php echo $baiThi['noi_dung']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 