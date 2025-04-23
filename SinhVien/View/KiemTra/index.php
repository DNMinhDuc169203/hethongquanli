<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách bài thi</title>
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
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 250px;">
        <div class="sidebar-header">
            <i class="fas fa-graduation-cap fa-2x"></i>
            <h3>Sinh viên</h3>
            <p class="small text-muted mb-0"><?php echo htmlspecialchars($_SESSION['ho_va_ten']); ?></p>
        </div>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/index.php?controller=home" class="nav-link">
                    <i class="fas fa-home"></i> Trang chủ
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra" class="nav-link active">
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
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-edit me-2"></i>Danh sách bài thi</h2>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
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
                                        <th>Môn học</th>
                                        <th>Tên bài kiểm tra</th>
                                        <th>Thời gian làm</th>
                                        <th>Thời gian mở</th>
                                        <th>Số câu hỏi</th>
                                        <th>Số lần làm</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tracNghiemTests as $test): ?>
                                        <?php 
                                            // Đặt múi giờ Việt Nam
                                            date_default_timezone_set('Asia/Ho_Chi_Minh');
                                            
                                            // Đảm bảo múi giờ chính xác
                                            $timezone = new DateTimeZone(date_default_timezone_get());
                                            $now = new DateTime('now', $timezone);
                                            $startTime = new DateTime($test['thoi_gian_bat_dau'], $timezone);
                                            $endTime = isset($test['thoi_gian_ket_thuc']) ? new DateTime($test['thoi_gian_ket_thuc'], $timezone) : null;
                                            $canTake = true;
                                            $status = "";
                                            
                                            // Debug thông tin thời gian
                                            echo "<!-- DEBUG TIMEZONE: Server timezone=" . date_default_timezone_get() . ", Current time=" . date('Y-m-d H:i:s') . " -->";
                                            echo "<!-- DEBUG: Now=" . $now->format('Y-m-d H:i:s') . ", Start=" . $startTime->format('Y-m-d H:i:s');
                                            if ($endTime) {
                                                echo ", End=" . $endTime->format('Y-m-d H:i:s');
                                            }
                                            echo " -->";
                                            
                                            // Kiểm tra trạng thái dựa trên thời gian và số lần làm
                                            // Ưu tiên kiểm tra số lần làm trước
                                            if ($test['so_lan_da_lam'] >= $test['so_lan_lam'] && $test['so_lan_lam'] > 0) {
                                                $status = '<span class="badge bg-info">Đã hoàn thành</span>';
                                                $canTake = false;
                                            } else if ($now < $startTime) {
                                                $status = '<span class="badge bg-warning">Chưa mở</span>';
                                                $canTake = false;
                                            } else if ($endTime && $now > $endTime) {
                                                $status = '<span class="badge bg-secondary">Đã kết thúc</span>';
                                                $canTake = false;
                                            } else {
                                                $status = '<span class="badge bg-success">Đang mở</span>';
                                            }
                                        ?>
                                        <tr>
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
                                            <td><span class="badge bg-success"><?php echo isset($test['so_cau_hoi']) && $test['so_cau_hoi'] > 0 ? $test['so_cau_hoi'] . ' câu hỏi' : 'Chưa có câu hỏi'; ?></span></td>
                                            <td><?php echo $test['so_lan_da_lam']; ?>/<?php echo $test['so_lan_lam']; ?></td>
                                            <td><?php echo $status; ?></td>
                                            <td>
                                                <?php if ($canTake): ?>
                                                    <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=lambaithitracnghiem&id=<?php echo $test['id']; ?>" class="btn btn-primary btn-sm btn-lam-bai" onclick="return confirm('Bạn có chắc chắn muốn bắt đầu làm bài thi này không? Nếu có phiên làm dở, bạn sẽ tiếp tục từ đó. Nếu không có phiên làm dở, bạn sẽ bắt đầu một phiên mới.');">
                                                        <i class="fas fa-edit"></i> Làm bài
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=xemTracNghiem&id=<?php echo $test['id']; ?>" class="btn btn-secondary btn-sm">
                                                        <i class="fas fa-info-circle"></i> Chi tiết
                                                    </a>
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
                                        <th>Môn học</th>
                                        <th>Tên bài kiểm tra</th>
                                        <th>Thời gian làm</th>
                                        <th>Thời gian mở</th>
                                        <th>Số lần làm</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tuLuanTests as $test): ?>
                                        <?php 
                                            // Đặt múi giờ Việt Nam
                                            date_default_timezone_set('Asia/Ho_Chi_Minh');
                                            
                                            $timezone = new DateTimeZone(date_default_timezone_get());
                                            $now = new DateTime('now', $timezone);
                                            $startTime = new DateTime($test['thoi_gian_bat_dau'], $timezone);
                                            $endTime = isset($test['thoi_gian_ket_thuc']) ? new DateTime($test['thoi_gian_ket_thuc'], $timezone) : null;
                                            $canTake = true;
                                            $status = "";
                                            
                                            // Debug thông tin thời gian
                                            echo "<!-- DEBUG TIMEZONE: Server timezone=" . date_default_timezone_get() . ", Current time=" . date('Y-m-d H:i:s') . " -->";
                                            echo "<!-- DEBUG: Now=" . $now->format('Y-m-d H:i:s') . ", Start=" . $startTime->format('Y-m-d H:i:s');
                                            if ($endTime) {
                                                echo ", End=" . $endTime->format('Y-m-d H:i:s');
                                            }
                                            echo " -->";
                                            
                                            // Kiểm tra trạng thái dựa trên kết quả và thời gian
                                            // Ưu tiên kiểm tra đã nộp hay chưa
                                            if ($test['da_nop'] && isset($test['so_lan_nop']) && isset($test['so_lan_lam']) && $test['so_lan_nop'] >= $test['so_lan_lam']) {
                                                $status = '<span class="badge bg-info">Đã hoàn thành</span>';
                                                $canTake = false;
                                            } else if ($now < $startTime) {
                                                $status = '<span class="badge bg-warning">Chưa mở</span>';
                                                $canTake = false;
                                            } else if ($endTime && $now > $endTime) {
                                                $status = '<span class="badge bg-secondary">Đã kết thúc</span>';
                                                $canTake = false;
                                            } else {
                                                $status = '<span class="badge bg-success">Đang mở</span>';
                                                // Nếu đã nộp bài nhưng còn lượt làm
                                                if ($test['da_nop']) {
                                                    $status = '<span class="badge bg-primary">Có thể làm lại</span>';
                                                }
                                            }
                                        ?>
                                        <tr>
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
                                            <td><?php echo isset($test['so_lan_nop']) ? $test['so_lan_nop'] : 0; ?>/<?php echo isset($test['so_lan_lam']) ? $test['so_lan_lam'] : '-'; ?></td>
                                            <td><?php echo $status; ?></td>
                                            <td>
                                                <?php if ($canTake): ?>
                                                    <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=lamBaiThiTuLuan&id=<?php echo $test['id']; ?>" class="btn btn-primary btn-sm btn-lam-bai" onclick="return confirm('Bạn có chắc chắn muốn bắt đầu làm bài thi này không?');">
                                                        <i class="fas fa-edit"></i> Làm bài
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=xemTuLuan&id=<?php echo $test['id']; ?>" class="btn btn-secondary btn-sm">
                                                        <i class="fas fa-info-circle"></i> Chi tiết
                                                    </a>
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
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 