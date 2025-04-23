<?php
// Đặt múi giờ Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Hệ thống quản lý môn học</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #eee;
            font-weight: 600;
            padding: 15px 20px;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            font-weight: 600;
            color: #333;
        }
        .btn-sm {
            border-radius: 4px;
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        .badge {
            padding: 6px 10px;
            font-weight: 500;
        }
        .nav-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: #6c757d;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .nav-tabs .nav-link.active {
            border-bottom: 2px solid #3a7bd5;
            color: #3a7bd5;
        }
        .nav-tabs .nav-link i {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 250px;">
        <div class="sidebar-header">
            <i class="fas fa-graduation-cap fa-2x"></i>
            <h3>Sinh viên</h3>
            <p class="small text-white mb-0"><?php echo htmlspecialchars($_SESSION['ho_va_ten'] ?? ''); ?></p>
        </div>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/index.php?controller=home" class="nav-link active" aria-current="page">
                    <i class="fas fa-home"></i> Trang chủ
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra" class="nav-link">
                    <i class="fas fa-edit"></i> Làm bài thi
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=lichSu" class="nav-link">
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
        <?php 
            // Debug timezone info
                echo "<!-- DEBUG TIMEZONE: Server timezone=" . date_default_timezone_get() . ", Current time=" . date('Y-m-d H:i:s') . " -->";
        ?>
        
        <?php if (isset($error) && !empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <!-- Thông tin sinh viên -->
        <?php if (isset($sinhVienInfo) && $sinhVienInfo): ?>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin sinh viên</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <th width="40%">Mã số sinh viên:</th>
                                    <td><?php echo htmlspecialchars($user['ma_so']); ?></td>
                                </tr>
                                <tr>
                                    <th>Họ và tên:</th>
                                    <td><?php echo htmlspecialchars($user['ho_va_ten']); ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <th width="40%">Lớp:</th>
                                    <td><?php echo htmlspecialchars($sinhVienInfo['ten_lop']); ?></td>
                                </tr>
                                <tr>
                                    <th>Ngành học:</th>
                                    <td><?php echo htmlspecialchars($sinhVienInfo['nganh_hoc']); ?></td>
                                </tr>
                                <tr>
                                    <th>Năm nhập học:</th>
                                    <td><?php echo htmlspecialchars($sinhVienInfo['nam_nhap_hoc']); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Danh sách bài kiểm tra trắc nghiệm -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Bài kiểm tra trắc nghiệm</h5>
            </div>
            <div class="card-body">
                <?php if (empty($tracNghiemTests)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Không có bài kiểm tra trắc nghiệm nào.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã môn</th>
                                    <th>Tên môn học</th>
                                    <th>Tên bài kiểm tra</th>
                                    <th>Thời gian làm</th>
                                    <th>Thời hạn</th>
                                    <th>Điểm cao nhất</th>
                                    <th>Trạng thái</th>
                                    <!-- <th>Thao tác</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tracNghiemTests as $test): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($test['ma_mon']); ?></td>
                                        <td><?php echo htmlspecialchars($test['ten_mon']); ?></td>
                                        <td><?php echo htmlspecialchars($test['tieu_de']); ?></td>
                                        <td><?php echo $test['thoi_gian_lam'] ? $test['thoi_gian_lam'] . ' phút' : 'Không giới hạn'; ?></td>
                                        <td>
                                            <?php 
                                                echo date('d/m/Y H:i', strtotime($test['thoi_gian_bat_dau'])); 
                                                if ($test['thoi_gian_ket_thuc']) {
                                                    echo ' - ' . date('d/m/Y H:i', strtotime($test['thoi_gian_ket_thuc']));
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if (isset($test['diem_cao_nhat'])) {
                                                    echo '<span class="badge bg-' . ($test['diem_cao_nhat'] >= 5 ? 'success' : 'danger') . '">' . 
                                                         number_format($test['diem_cao_nhat'], 1) . '</span>';
                                                } else {
                                                    echo '<span class="badge bg-secondary">Chưa làm</span>';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                // Đặt múi giờ Việt Nam
                                                date_default_timezone_set('Asia/Ho_Chi_Minh');
                                                
                                                // Đảm bảo múi giờ chính xác
                                                $timezone = new DateTimeZone(date_default_timezone_get());
                                                $now = new DateTime('now', $timezone);
                                                $startTime = new DateTime($test['thoi_gian_bat_dau'], $timezone);
                                                $endTime = isset($test['thoi_gian_ket_thuc']) ? new DateTime($test['thoi_gian_ket_thuc'], $timezone) : null;
                                                $isCompleted = isset($test['diem_cao_nhat']);
                                                
                                                echo "<!-- DEBUG: Now=" . $now->format('Y-m-d H:i:s') . ", Start=" . $startTime->format('Y-m-d H:i:s');
                                                if ($endTime) {
                                                    echo ", End=" . $endTime->format('Y-m-d H:i:s');
                                                }
                                                echo " -->";
                                                
                                                // Kiểm tra trạng thái dựa trên thời gian và kết quả
                                                // Ưu tiên hiển thị đã hoàn thành nếu đã làm bài
                                                if ($isCompleted) {
                                                    echo '<span class="badge bg-info">Đã hoàn thành</span>';
                                                } else if ($now < $startTime) {
                                                    echo '<span class="badge bg-warning">Chưa mở</span>';
                                                } else if ($endTime && $now > $endTime) {
                                                    echo '<span class="badge bg-secondary">Đã kết thúc</span>';
                                                } else {
                                                    echo '<span class="badge bg-success">Đang mở</span>';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if (isset($test['diem_cao_nhat'])): ?>
                                                <!-- <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=xemTracNghiem&id=<?php echo $test['id']; ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Xem lại
                                                </a> -->
                                            <?php else: ?>
                                                <!-- <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=xemTracNghiem&id=<?php echo $test['id']; ?>" class="btn btn-primary btn-sm" onclick="return confirm('Bạn có chắc chắn muốn bắt đầu làm bài thi này không?');">
                                                    <i class="fas fa-edit"></i> Làm bài
                                                </a> -->
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Danh sách bài kiểm tra tự luận -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-pen-alt me-2"></i>Bài kiểm tra tự luận</h5>
            </div>
            <div class="card-body">
                <?php if (empty($tuLuanTests)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Không có bài kiểm tra tự luận nào.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã môn</th>
                                    <th>Tên môn học</th>
                                    <th>Tên bài kiểm tra</th>
                                    <th>Thời gian làm</th>
                                    <th>Thời hạn</th>
                                    <th>Điểm</th>
                                    <th>Trạng thái</th>
                                    <!-- <th>Thao tác</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tuLuanTests as $test): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($test['ma_mon']); ?></td>
                                        <td><?php echo htmlspecialchars($test['ten_mon']); ?></td>
                                        <td><?php echo htmlspecialchars($test['tieu_de']); ?></td>
                                        <td><?php echo $test['thoi_gian_lam'] ? $test['thoi_gian_lam'] . ' phút' : 'Không giới hạn'; ?></td>
                                        <td>
                                            <?php 
                                                echo date('d/m/Y H:i', strtotime($test['thoi_gian_bat_dau'])); 
                                                if ($test['thoi_gian_ket_thuc']) {
                                                    echo ' - ' . date('d/m/Y H:i', strtotime($test['thoi_gian_ket_thuc']));
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if (isset($test['diem'])) {
                                                    echo '<span class="badge bg-' . ($test['diem'] >= 5 ? 'success' : 'danger') . '">' . 
                                                         number_format($test['diem'], 1) . '</span>';
                                                } else if ($test['da_nop']) {
                                                    echo '<span class="badge bg-info">Đã nộp</span>';
                                                } else {
                                                    echo '<span class="badge bg-secondary">Chưa làm</span>';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                // Đặt múi giờ Việt Nam
                                                date_default_timezone_set('Asia/Ho_Chi_Minh');
                                                
                                                // Đảm bảo múi giờ chính xác
                                                $timezone = new DateTimeZone(date_default_timezone_get());
                                                $now = new DateTime('now', $timezone);
                                                $startTime = new DateTime($test['thoi_gian_bat_dau'], $timezone);
                                                $endTime = isset($test['thoi_gian_ket_thuc']) ? new DateTime($test['thoi_gian_ket_thuc'], $timezone) : null;
                                                $isCompleted = isset($test['diem']) || (!empty($test['da_nop']));
                                                
                                                echo "<!-- DEBUG: Now=" . $now->format('Y-m-d H:i:s') . ", Start=" . $startTime->format('Y-m-d H:i:s');
                                                if ($endTime) {
                                                    echo ", End=" . $endTime->format('Y-m-d H:i:s');
                                                }
                                                echo " -->";
                                                
                                                // Kiểm tra trạng thái dựa trên thời gian và kết quả
                                                // Ưu tiên hiển thị đã hoàn thành nếu đã làm bài
                                                if ($isCompleted) {
                                                    echo '<span class="badge bg-info">Đã hoàn thành</span>';
                                                } else if ($now < $startTime) {
                                                    echo '<span class="badge bg-warning">Chưa mở</span>';
                                                } else if ($endTime && $now > $endTime) {
                                                    echo '<span class="badge bg-secondary">Đã kết thúc</span>';
                                                } else {
                                                    echo '<span class="badge bg-success">Đang mở</span>';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if (isset($test['diem']) || (!empty($test['da_nop']))): ?>
                                                <!-- <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=xemTuLuan&id=<?php echo $test['id']; ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Xem lại
                                                </a> -->
                                            <?php else: ?>
                                                <!-- <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=xemTuLuan&id=<?php echo $test['id']; ?>" class="btn btn-primary btn-sm" onclick="return confirm('Bạn có chắc chắn muốn bắt đầu làm bài thi này không?');">
                                                    <i class="fas fa-edit"></i> Làm bài
                                                </a> -->
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 