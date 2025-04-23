<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết bài thi</title>
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
        .exam-details {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .question-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .answer-option {
            margin-left: 20px;
            margin-bottom: 5px;
        }
        .correct-answer {
            color: #198754;
            font-weight: bold;
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
                    <h2>Chi tiết bài thi</h2>
                    <div>
                        <?php if (!isset($_GET['show_answer']) || $_GET['show_answer'] != 1): ?>
                            <a href="index.php?controller=baithi&action=view&id=<?php echo $baiThi['id']; ?>&show_answer=1" class="btn btn-info me-2">Hiển thị đáp án</a>
                        <?php else: ?>
                            <a href="index.php?controller=baithi&action=view&id=<?php echo $baiThi['id']; ?>" class="btn btn-secondary me-2">Ẩn đáp án</a>
                        <?php endif; ?>
                        <!-- <a href="index.php?controller=baithi&action=edit&id=<?php echo $baiThi['id']; ?>" class="btn btn-warning">Sửa bài thi</a> -->
                        <a href="index.php?controller=baithi" class="btn btn-secondary">Quay lại</a>
                    </div>
                </div>
                
                <div class="exam-details">
                    <h3><?php echo htmlspecialchars($baiThi['tieu_de']); ?></h3>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p><strong>Mô tả:</strong><br><?php echo nl2br(htmlspecialchars($baiThi['mo_ta'])); ?></p>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <?php if ($baiThi['thoi_gian_lam'] !== null): ?>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold ">Thời gian làm bài:</label>
                                    <p><?php echo $baiThi['thoi_gian_lam']; ?> phút</p>
                                </div>
                                <?php endif; ?>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Thời gian bắt đầu:</label>
                                    <p><?php 
                                        date_default_timezone_set('Asia/Ho_Chi_Minh');
                                        echo date('H:i d/m/Y', strtotime($baiThi['thoi_gian_bat_dau'])); 
                                    ?></p>
                                </div>
                            </div>
                            <?php if ($baiThi['thoi_gian_ket_thuc']): ?>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Thời gian kết thúc:</label>
                                    <p><?php 
                                        date_default_timezone_set('Asia/Ho_Chi_Minh');
                                        echo date('H:i d/m/Y', strtotime($baiThi['thoi_gian_ket_thuc'])); 
                                    ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                            <p><strong>Số lần làm tối đa:</strong> <?php echo $baiThi['so_lan_lam']; ?></p>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <p><strong>Trộn câu hỏi:</strong> <?php echo $baiThi['tron_cau_hoi'] ? 'Có' : 'Không'; ?></p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Trộn đáp án:</strong> <?php echo $baiThi['tron_dap_an'] ? 'Có' : 'Không'; ?></p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Hiển thị đáp án:</strong> <?php echo $baiThi['hien_thi_dap_an'] ? 'Có' : 'Không'; ?></p>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <?php 
                                // Lấy thông tin lớp học
                                $lopHoc = $this->giaoVienModel->getLopHocById($baiThi['lop_hoc_id']);
                                
                                // Lấy thông tin môn học
                                require_once 'Model/MonHocModel.php';
                                $monHocModel = new MonHocModel();
                                $monHoc = $monHocModel->getMonHocById($baiThi['mon_hoc_id']);
                            ?>
                            <div class="alert alert-primary">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong><i class="fas fa-users me-2"></i>Lớp học:</strong> <?php echo htmlspecialchars($lopHoc['ten_lop']); ?> (<?php echo htmlspecialchars($lopHoc['ma_lop']); ?>)</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong><i class="fas fa-book me-2"></i>Môn học:</strong> <?php echo htmlspecialchars($monHoc['ten_mon']); ?> (<?php echo htmlspecialchars($monHoc['ma_mon']); ?>)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($chuDeTrongBaiThi)): ?>
                    <div class="row mt-2">
                        <div class="col-12">
                            <p><strong>Chủ đề:</strong> 
                                <?php foreach ($chuDeTrongBaiThi as $index => $chuDe): ?>
                                    <span class="badge bg-info"><?php echo htmlspecialchars($chuDe['ten_chu_de']); ?></span>
                                    <?php if ($index < count($chuDeTrongBaiThi) - 1) echo ' '; ?>
                                <?php endforeach; ?>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <h4 class="mb-3">Danh sách câu hỏi</h4>
                <?php if (empty($chuDeTrongBaiThi)): ?>
                    <p>Chưa có câu hỏi nào trong bài thi.</p>
                <?php else: ?>
                    <?php $soCauHoi = 1; ?>
                    <?php foreach ($cauHoiTheoChuDe as $chuDeId => $chuDeData): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Chủ đề: <?php echo htmlspecialchars($chuDeData['chu_de']['ten_chu_de']); ?></h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($chuDeData['cau_hoi'])): ?>
                                    <p class="text-muted">Không có câu hỏi nào trong chủ đề này.</p>
                                <?php else: ?>
                                    <?php foreach ($chuDeData['cau_hoi'] as $cauHoi): ?>
                                        <div class="question-box">
                                            <h5>Câu <?php echo $soCauHoi++; ?></h5>
                                            <p><?php echo nl2br(htmlspecialchars($cauHoi['noi_dung'])); ?></p>
                                            <?php 
                                            $dapAn = $cauHoi['dap_an'];
                                            $dapAnDung = $cauHoi['dap_an_dung'];
                                            // Kết hợp tất cả các đáp án để hiển thị
                                            $tatCaDapAn = array_merge($dapAn, $dapAnDung);
                                            
                                            if (!empty($tatCaDapAn)): 
                                            ?>
                                                <div class="answers">
                                                    <?php foreach ($tatCaDapAn as $key => $value): ?>
                                                        <div class="answer-option">
                                                            <?php echo chr(65 + $key) . '. ' . htmlspecialchars($value); ?>
                                                            <?php if ($baiThi['hien_thi_dap_an'] == 1 && in_array($value, $dapAnDung)): ?>
                                                                <span class="badge bg-success">Đáp án đúng</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 