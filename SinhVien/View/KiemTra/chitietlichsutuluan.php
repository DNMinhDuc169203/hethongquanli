<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết bài thi tự luận - <?php echo htmlspecialchars($test['tieu_de'] ?? ''); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 0;
            box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);
        }
        .sidebar-header {
            padding: 20px 15px;
            text-align: center;
            border-bottom: 1px solid #434b56;
        }
        .sidebar-header h3 {
            margin-top: 15px;
            font-size: 1.5rem;
            color: white;
        }
        .nav-pills .nav-link {
            color: #ced4da;
            border-radius: 0;
            padding: 12px 20px;
            margin: 5px 0;
            transition: all 0.3s ease;
        }
        .nav-pills .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .nav-pills .nav-link.active {
            background-color: #3a7bd5;
            color: white;
        }
        .nav-pills .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .file-download {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .file-download i {
            margin-right: 8px;
            color: #0d6efd;
        }
        .content-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 250px;">
    <div class="sidebar-header">
        <i class="fas fa-graduation-cap fa-2x"></i>
        <h3>Sinh viên</h3>
        <p class="small text-muted mb-0"><?php echo htmlspecialchars($_SESSION['ho_va_ten'] ?? ''); ?></p>
    </div>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/index.php?controller=home" class="nav-link">
                <i class="fas fa-home"></i> Trang chủ
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra" class="nav-link">
                <i class="fas fa-edit"></i> Làm bài thi
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=lichSu" class="nav-link active">
                <i class="fas fa-history"></i> Lịch sử làm bài
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/index.php?controller=auth&action=changePassword" class="nav-link">
                <i class="fas fa-key"></i> Đổi mật khẩu
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/index.php?controller=auth&action=logout" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<main class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-history me-2"></i>Chi tiết bài thi tự luận</h2>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary text-white">
                <h5 class="m-0 font-weight-bold">
                    <i class="fas fa-file-alt me-2"></i>Chi tiết bài thi: <?php echo htmlspecialchars($test['tieu_de'] ?? ''); ?>
                </h5>
                <div>
                    Môn học: <span class="fw-bold"><?php echo htmlspecialchars($test['ten_mon'] ?? ''); ?></span>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Thông tin kết quả bài thi -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin bài thi</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <tr>
                                        <th width="40%">Tên bài thi:</th>
                                        <td><?php echo htmlspecialchars($test['tieu_de'] ?? ''); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Thời gian làm bài:</th>
                                        <td><?php echo isset($test['thoi_gian_lam']) ? $test['thoi_gian_lam'] . ' phút' : 'Không giới hạn'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Thời gian mở:</th>
                                        <td><?php echo isset($test['thoi_gian_bat_dau']) ? date('H:i:s d/m/Y', strtotime($test['thoi_gian_bat_dau'])) : 'Không xác định'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Thời gian đóng:</th>
                                        <td><?php echo isset($test['thoi_gian_ket_thuc']) ? date('H:i:s d/m/Y', strtotime($test['thoi_gian_ket_thuc'])) : 'Không xác định'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Ngày nộp bài:</th>
                                        <td>
                                            <?php 
                                            if (isset($baiLam['ngay_nop'])) {
                                                echo date('H:i:s d/m/Y', strtotime($baiLam['ngay_nop']));
                                            } else {
                                                echo 'Chưa xác định';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                                
                                <?php if (isset($test['mo_ta']) && !empty($test['mo_ta'])): ?>
                                <div class="mt-3">
                                    <h6 class="fw-bold"><i class="fas fa-info-circle"></i> Mô tả bài thi:</h6>
                                    <div class="content-box">
                                        <?php echo nl2br(htmlspecialchars($test['mo_ta'])); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($test['noi_dung']) && !empty($test['noi_dung'])): ?>
                                <div class="mt-3">
                                    <h6 class="fw-bold"><i class="fas fa-question-circle"></i> Nội dung đề bài:</h6>
                                    <div class="content-box">
                                        <?php echo $test['noi_dung']; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-award"></i> Kết quả</h5>
                            </div>
                            <div class="card-body text-center">
                                <?php if (isset($baiLam['diem'])): ?>
                                    <h1 class="display-1 <?php echo $baiLam['diem'] >= 5 ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo number_format($baiLam['diem'], 1); ?>
                                    </h1>
                                    <p class="lead">Điểm số (thang điểm 10)</p>
                                <?php else: ?>
                                    <h3 class="text-warning">
                                        <i class="fas fa-clock me-2"></i>Chưa chấm điểm
                                    </h3>
                                    <p class="lead">Bài làm đang được giáo viên chấm điểm</p>
                                <?php endif; ?>
                                
                                <?php if (isset($baiLam['nhan_xet']) && !empty($baiLam['nhan_xet'])): ?>
                                    <div class="mt-4 text-start">
                                        <h5 class="fw-bold border-bottom pb-2"><i class="fas fa-comment-dots me-2"></i>Nhận xét của giáo viên:</h5>
                                        <div class="p-3 content-box">
                                            <?php echo nl2br(htmlspecialchars($baiLam['nhan_xet'])); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Chi tiết bài nộp -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Nội dung bài làm</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($test['noi_dung']) && !empty($test['noi_dung'])): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-book me-2"></i>Đề bài</h6>
                            </div>
                            <div class="card-body border-bottom pb-4">
                                <?php echo nl2br(htmlspecialchars($test['noi_dung'])); ?>
                                
                                <?php if (isset($test['dinh_kem']) && !empty($test['dinh_kem'])): ?>
                                    <div class="mt-3">
                                        <h6 class="fw-bold"><i class="fas fa-paperclip"></i> File đề bài đính kèm:</h6>
                                        <div class="file-download">
                                            <i class="fas fa-file-download"></i>
                                            <a href="<?php echo htmlspecialchars($test['dinh_kem']); ?>" target="_blank">
                                                <?php echo basename($test['dinh_kem']); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <h6 class="mb-3 fw-bold"><i class="fas fa-pen-alt me-2"></i>Bài làm của tôi:</h6>
                        <?php if (isset($baiLam['noi_dung']) && !empty($baiLam['noi_dung'])): ?>
                            <div class="content-box mb-4">
                                <?php echo nl2br(htmlspecialchars($baiLam['noi_dung'])); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted fst-italic">Không có nội dung văn bản.</p>
                        <?php endif; ?>
                        
                        <?php if (isset($baiLam['tep_tin']) && !empty($baiLam['tep_tin'])): ?>
                            <h6 class="mt-4 mb-3 fw-bold"><i class="fas fa-paperclip me-2"></i>File đính kèm:</h6>
                            <?php 
                            $files = is_array($baiLam['tep_tin']) ? $baiLam['tep_tin'] : explode(',', $baiLam['tep_tin']);
                            foreach ($files as $file): 
                                if (empty(trim($file))) continue;
                                $fileName = basename($file);
                                $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                                $iconClass = 'fas fa-file';
                                
                                // Xác định biểu tượng dựa trên phần mở rộng của tệp
                                switch (strtolower($fileExt)) {
                                    case 'pdf': $iconClass = 'fas fa-file-pdf'; break;
                                    case 'doc': 
                                    case 'docx': $iconClass = 'fas fa-file-word'; break;
                                    case 'xls': 
                                    case 'xlsx': $iconClass = 'fas fa-file-excel'; break;
                                    case 'jpg': 
                                    case 'jpeg': 
                                    case 'png': $iconClass = 'fas fa-file-image'; break;
                                    case 'txt': $iconClass = 'fas fa-file-alt'; break;
                                }
                            ?>
                                <div class="file-download">
                                    <i class="<?php echo $iconClass; ?>"></i>
                                    <a href="<?php echo htmlspecialchars($file); ?>" target="_blank">
                                        <?php echo htmlspecialchars($fileName); ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted fst-italic">Không có file đính kèm.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=lichSu" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại lịch sử bài thi
                    </a>
                    
                    <!-- Kiểm tra nếu bài thi vẫn còn mở và chưa đạt đến số lần làm tối đa -->
                    <?php 
                    $now = new DateTime();
                    $endTime = !empty($test['thoi_gian_ket_thuc']) ? new DateTime($test['thoi_gian_ket_thuc']) : null;
                    
                    // Kiểm tra nếu bài thi vẫn còn mở và chưa vượt quá số lần nộp tối đa
                    $canSubmitAgain = $endTime && $now < $endTime && 
                                    (!isset($test['so_lan_nop']) || !isset($test['so_lan_nop_toi_da']) || 
                                    $test['so_lan_nop'] < $test['so_lan_nop_toi_da']);
                    
                    if ($canSubmitAgain): 
                    ?>
                        <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=xemTuLuan&id=<?php echo $test['id']; ?>" class="btn btn-primary ms-2">
                            <i class="fas fa-edit me-2"></i>Làm bài lại
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 