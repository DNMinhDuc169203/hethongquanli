<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử bài thi</title>
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
            <p class="small text-muted mb-0"><?php echo htmlspecialchars($_SESSION['ho_va_ten']); ?></p>
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
                <h2><i class="fas fa-history me-2"></i>Lịch sử bài thi</h2>
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
            
            <?php
            // Kiểm tra xem có phiên đang làm dở không
            $hasUnfinishedSessions = false;
            if (isset($tracNghiemHistory)) {
                // Lọc ra các bài thi có phiên đang làm dở
                $phienDangDo = array_filter($tracNghiemHistory, function($item) {
                    return isset($item['trang_thai_phien']) && $item['trang_thai_phien'] === 'dang_lam';
                });
                $hasUnfinishedSessions = !empty($phienDangDo);
            }
            
            if ($hasUnfinishedSessions): 
            ?>
            <!-- Phần hiển thị bài kiểm tra đang làm dở đã được xóa -->
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-4" id="testTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="trac-nghiem-tab" data-bs-toggle="tab" href="#trac-nghiem" role="tab">
                                <i class="fas fa-check-square"></i> Trắc nghiệm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tu-luan-tab" data-bs-toggle="tab" href="#tu-luan" role="tab">
                                <i class="fas fa-edit"></i> Tự luận
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="testTabsContent">
                        <!-- Trắc nghiệm -->
                        <div class="tab-pane fade show active" id="trac-nghiem" role="tabpanel">
                            <?php if (empty($tracNghiemHistory)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> Bạn chưa làm bài kiểm tra trắc nghiệm nào.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>STT</th>
                                                <th>Bài kiểm tra</th>
                                                <th>Môn học</th>
                                                <th>Ngày làm</th>
                                                <th>Điểm</th>
                                                <th>Số câu đúng</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            // Lọc ra chỉ hiển thị các bài đã nộp
                                            $submitted = array_filter($tracNghiemHistory, function($item) {
                                                return !isset($item['trang_thai_phien']) || $item['trang_thai_phien'] !== 'dang_lam';
                                            });
                                            
                                            foreach ($submitted as $index => $history): 
                                            ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>
                                                    <td><?php echo htmlspecialchars($history['tieu_de']); ?></td>
                                                    <td><?php echo htmlspecialchars($history['ten_mon']); ?></td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($history['thoi_gian_nop'])); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo isset($history['diem']) && $history['diem'] >= 5 ? 'success' : 'danger'; ?>">
                                                            <?php echo isset($history['diem']) ? number_format($history['diem'], 1) : 'Chưa chấm'; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php echo isset($history['so_cau_dung']) ? $history['so_cau_dung'] : (isset($history['diem']) ? round(($history['diem'] / 10) * $history['so_cau_hoi']) : '?'); ?>/<?php echo $history['so_cau_hoi']; ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=chiTietLichSu&id=<?php echo $history['bai_kiem_tra_id']; ?>&bai_lam_id=<?php echo $history['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i> Xem
                                                            </a>
                                                            <?php
                                                            // Kiểm tra nếu có thông tin về số lần làm và số lần đã làm
                                                            if (isset($history['so_lan_lam']) && isset($history['so_lan_da_lam']) && $history['so_lan_lam'] > 0 && $history['so_lan_da_lam'] < $history['so_lan_lam']) {
                                                                echo '<a href="'.BASE_URL.'/index.php?controller=kiemtra&action=lambaithitracnghiem&id='.$history['bai_kiem_tra_id'].'&start=1" class="btn btn-sm btn-outline-success" onclick="return confirm(\'Bạn có chắc chắn muốn làm lại bài thi này không? Bạn sẽ bắt đầu với một phiên mới và không còn dữ liệu cũ.\');">
                                                                    <i class="fas fa-redo"></i> Làm lại
                                                                </a>';
                                                            }
                                                            ?>
                                                           
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Tự luận -->
                        <div class="tab-pane fade" id="tu-luan" role="tabpanel">
                            <?php if (empty($tuLuanHistory)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> Bạn chưa làm bài kiểm tra tự luận nào.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>STT</th>
                                                <th>Bài kiểm tra</th>
                                                <th>Môn học</th>
                                                <th>Ngày nộp</th>
                                                <th>Điểm</th>
                                                <th>Trạng thái</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($tuLuanHistory as $index => $history): ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>
                                                    <td><?php echo htmlspecialchars($history['tieu_de']); ?></td>
                                                    <td><?php echo htmlspecialchars($history['ten_mon']); ?></td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($history['ngay_nop'])); ?></td>
                                                    <td>
                                                        <?php if (isset($history['diem'])): ?>
                                                            <span class="badge bg-<?php echo $history['diem'] >= 5 ? 'success' : 'danger'; ?>">
                                                                <?php echo number_format($history['diem'], 1); ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Chưa chấm</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        $statusClass = 'secondary';
                                                        $statusText = 'Chưa xác định';
                                                        
                                                        if (isset($history['trang_thai'])) {
                                                            switch ($history['trang_thai']) {
                                                                case 'da_nop':
                                                                    $statusClass = 'info';
                                                                    $statusText = 'Đã nộp';
                                                                    break;
                                                                case 'dang_cham':
                                                                    $statusClass = 'warning';
                                                                    $statusText = 'Đang chấm';
                                                                    break;
                                                                case 'da_cham':
                                                                    $statusClass = 'success';
                                                                    $statusText = 'Đã chấm';
                                                                    break;
                                                            }
                                                        } else {
                                                            if (isset($history['diem'])) {
                                                                $statusClass = 'success';
                                                                $statusText = 'Đã chấm';
                                                            } else {
                                                                $statusClass = 'info';
                                                                $statusText = 'Đã nộp';
                                                            }
                                                        }
                                                        ?>
                                                        <span class="badge bg-<?php echo $statusClass; ?>">
                                                            <?php echo $statusText; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=chitietlichsutuluan&id=<?php echo $history['bai_kiem_tra_id']; ?>&bai_lam_id=<?php echo $history['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i> Xem
                                                        </a>
                                                        <?php
                                                        // Kiểm tra nếu bài kiểm tra còn cho phép nộp lại
                                                        $now = new DateTime();
                                                        $startTime = !empty($history['thoi_gian_bat_dau']) ? new DateTime($history['thoi_gian_bat_dau']) : $now;
                                                        $endTime = !empty($history['thoi_gian_ket_thuc']) ? new DateTime($history['thoi_gian_ket_thuc']) : null;
                                                        
                                                        // Kiểm tra nếu bài thi vẫn còn mở và chưa vượt quá số lần nộp tối đa
                                                        $canSubmitAgain = ($endTime && $now < $endTime) && 
                                                                        (!isset($history['so_lan_nop']) || !isset($history['so_lan_nop_toi_da']) || 
                                                                         $history['so_lan_nop'] < $history['so_lan_nop_toi_da']);
                                                        
                                                        if ($canSubmitAgain): 
                                                        ?>
                                                            <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=lamBaiThiTuLuan&id=<?php echo $history['bai_kiem_tra_id']; ?>" class="btn btn-success btn-sm ms-2" onclick="return confirm('Bạn có chắc chắn muốn làm lại bài tự luận này không?');">
                                                                <i class="fas fa-redo"></i> Làm lại
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
            </div>
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var triggerTabList = [].slice.call(document.querySelectorAll('#testTabs a'));
            triggerTabList.forEach(function(triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl);
                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });
        });
    </script>
</body>
</html> 