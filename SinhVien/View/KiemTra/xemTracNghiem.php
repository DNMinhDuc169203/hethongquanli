<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem bài thi trắc nghiệm</title>
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
            <a href="<?php echo BASE_URL; ?>/index.php?controller=home" class="nav-link nav-menu-link">
                <i class="fas fa-home"></i> Trang chủ
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra" class="nav-link active">
                <i class="fas fa-edit"></i> Làm bài thi
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=lichSu" class="nav-link nav-menu-link">
                <i class="fas fa-history"></i> Lịch sử làm bài
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/index.php?controller=auth&action=changePassword" class="nav-link nav-menu-link">
                <i class="fas fa-key"></i> Đổi mật khẩu
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/index.php?controller=auth&action=logout" class="nav-link nav-menu-link">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<main class="main-content">
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-check-square"></i> <?php echo !empty($test['tieu_de']) ? htmlspecialchars($test['tieu_de']) : 'Bài kiểm tra'; ?>
                </h6>
                <div>
                    Môn học: <span class="font-weight-bold"><?php echo !empty($test['ten_mon']) ? htmlspecialchars($test['ten_mon']) : ''; ?></span>
                    &nbsp;|&nbsp;
                    Giáo viên: <span class="font-weight-bold"><?php echo !empty($test['ten_giao_vien']) ? htmlspecialchars($test['ten_giao_vien']) : 'Chưa xác định'; ?></span>
                </div>
            </div>
            <div class="card-body">
                <!-- Thông báo lỗi và cảnh báo -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['warning'])): ?>
                    <div class="alert alert-warning">
                        <?php echo $_SESSION['warning']; unset($_SESSION['warning']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                
                <?php 
                // Kiểm tra trạng thái bài thi
                // Đặt múi giờ Việt Nam
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                
                // Hiển thị debug info về timezone
                echo "<!-- DEBUG TIMEZONE: Server timezone=" . date_default_timezone_get() . ", Current time=" . date('Y-m-d H:i:s') . " -->";
                
                $now = new DateTime();
                
                // Kiểm tra null trước khi tạo DateTime
                $startTime = !empty($test['thoi_gian_bat_dau']) ? new DateTime($test['thoi_gian_bat_dau']) : $now;
                $endTime = !empty($test['thoi_gian_ket_thuc']) ? new DateTime($test['thoi_gian_ket_thuc']) : $now;
                
                // Debug thông tin thời gian
                echo "<!-- DEBUG: Now=" . $now->format('Y-m-d H:i:s') . ", Start=" . $startTime->format('Y-m-d H:i:s');
                if (!empty($test['thoi_gian_ket_thuc'])) {
                    echo ", End=" . $endTime->format('Y-m-d H:i:s');
                }
                echo " -->";
                
                $isPast = $now > $endTime;
                $isFuture = $now < $startTime;
                $isOngoing = !$isPast && !$isFuture;
                
                // Kiểm tra số lần làm bài
                $maxAttempts = isset($test['so_lan_lam']) ? $test['so_lan_lam'] : 0;
                $currentAttempts = isset($test['so_lan_da_lam']) ? $test['so_lan_da_lam'] : 0;
                $canAttempt = ($maxAttempts == 0 || $currentAttempts < $maxAttempts) && $isOngoing;
                ?>
                
                <!-- Kiểm tra thời gian làm bài -->
                <?php if ($isPast): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-lock"></i> Bài kiểm tra này đã đóng.
                    </div>
                <?php elseif ($isFuture): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-clock"></i> Bài kiểm tra này chưa mở. Sẽ mở vào: <?php echo date('H:i:s d/m/Y', strtotime($test['thoi_gian_bat_dau'])); ?>
                    </div>
                <?php elseif ($maxAttempts > 0 && $currentAttempts >= $maxAttempts): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i> Bạn đã sử dụng hết số lần làm bài (<?php echo $currentAttempts; ?>/<?php echo $maxAttempts; ?>).
                    </div>
                <?php elseif ($isOngoing): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-unlock"></i> Bài kiểm tra đang mở. 
                        <?php if ($maxAttempts > 0): ?>
                            Số lần làm còn lại: <?php echo $maxAttempts - $currentAttempts; ?>/<?php echo $maxAttempts; ?>.
                        <?php else: ?>
                            Không giới hạn số lần làm.
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Hiển thị thông tin số câu hỏi -->
              
                
                <!-- Thông tin bài thi -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-info-circle"></i> Thông tin bài thi
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-clock"></i> Thời gian mở:</span>
                                        <span class="badge bg-primary text-white">
                                            <?php echo !empty($test['thoi_gian_bat_dau']) ? date('H:i:s d/m/Y', strtotime($test['thoi_gian_bat_dau'])) : 'Chưa xác định'; ?>
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-clock"></i> Thời gian đóng:</span>
                                        <span class="badge bg-danger text-white">
                                            <?php echo !empty($test['thoi_gian_ket_thuc']) ? date('H:i:s d/m/Y', strtotime($test['thoi_gian_ket_thuc'])) : 'Chưa xác định'; ?>
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-stopwatch"></i> Thời gian làm bài:</span>
                                        <span class="badge bg-warning text-dark">
                                            <?php echo isset($test['thoi_gian_lam']) ? $test['thoi_gian_lam'] . ' phút' : 'Không giới hạn'; ?>
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                     <?php if (isset($test['so_cau_hoi']) && $test['so_cau_hoi'] > 0): ?>
                                        <span><i class="fas fa-list-ol"></i> Số câu hỏi trong bài:</span>
                                        <span class="badge bg-success text-white">
                                            <?php echo $test['so_cau_hoi']; ?> câu hỏi
                                        </span>
                                    <?php elseif (isset($test['so_cau_hoi']) && $test['so_cau_hoi'] == 0): ?>
                                        <span><i class="fas fa-exclamation-triangle"></i> Số câu hỏi trong bài:</span>
                                        <span class="badge bg-warning text-dark">
                                            Chưa có câu hỏi
                                        </span>
                                    <?php endif; ?>
                                    </li>
                                    <!-- <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-redo"></i> Số lần làm:</span>
                                        <span class="badge bg-info text-white">
                                            <?php echo isset($test['so_lan_da_lam']) ? $test['so_lan_da_lam'] : '0'; ?>/<?php echo isset($test['so_lan_lam']) ? $test['so_lan_lam'] : '∞'; ?>
                                        </span> 
                                    </li> -->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-user-graduate"></i> Kết quả của bạn
                            </div>
                            <div class="card-body">
                                <?php if (isset($previousResult) && $previousResult): ?>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-trophy"></i> Điểm số:</span>
                                            <span class="badge bg-<?php echo isset($previousResult['diem']) && $previousResult['diem'] >= 5 ? 'success' : 'danger'; ?> text-white">
                                                <?php echo isset($previousResult['diem']) ? number_format($previousResult['diem'], 1) : '0'; ?>/10
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-check"></i> Số câu đúng:</span>
                                            <span class="badge bg-primary text-white">
                                                <?php echo isset($previousResult['so_cau_dung']) ? $previousResult['so_cau_dung'] : '0'; ?>/<?php echo isset($test['so_cau_hoi']) ? $test['so_cau_hoi'] : (isset($questions) ? count($questions) : '0'); ?>
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-calendar-check"></i> Ngày làm:</span>
                                            <span>
                                                <?php echo isset($previousResult['ngay_lam']) ? date('H:i:s d/m/Y', strtotime($previousResult['ngay_lam'])) : ''; ?>
                                            </span>
                                        </li>
                                    </ul>
                                <?php else: ?>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Bạn chưa làm bài kiểm tra này.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if ($canAttempt && (!isset($test['so_cau_hoi']) || $test['so_cau_hoi'] > 0)): ?>
                    <div class="text-center mb-4">
                        <!-- <a href="index.php?controller=kiemtra&action=lambaithitracnghiem&id=<?php echo isset($test['id']) ? $test['id'] : (isset($_GET['id']) ? $_GET['id'] : '0'); ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-play-circle"></i> Bắt đầu làm bài
                        </a> -->
                    </div>
                <?php elseif (!$canAttempt && isset($test['so_cau_hoi']) && $test['so_cau_hoi'] == 0): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Bài kiểm tra này chưa có câu hỏi. Vui lòng liên hệ giáo viên.
                    </div>
                <?php endif; ?>
                
                <!-- Hiển thị kết quả nếu đã làm bài -->
                <?php if (isset($previousResult) && $previousResult): ?>
                    <!-- <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-list-ol"></i> Chi tiết bài làm</h5>
                        </div>
                        <div class="card-body">
                            <?php 
                            // Lấy đáp án và câu trả lời của user
                            $userAnswers = [];
                            if (isset($previousResult['chi_tiet']) && is_array($previousResult['chi_tiet'])) {
                                foreach ($previousResult['chi_tiet'] as $answer) {
                                    $userAnswers[$answer['cau_hoi_id']] = $answer['lua_chon'];
                                }
                            }
                            ?>
                            
                            <?php foreach ($questions as $index => $question): ?>
                                <div class="card mb-3">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            Câu <?php echo $index + 1; ?>: <?php echo htmlspecialchars($question['noi_dung']); ?>
                                        </h6>
                                        <?php if (!empty($question['ten_chu_de'])): ?>
                                        <span class="badge bg-info text-white">
                                            <i class="fas fa-tag"></i> <?php echo htmlspecialchars($question['ten_chu_de']); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <div class="options-list">
                                            <?php 
                                            $options = [
                                                'A' => isset($question['dap_an_a']) ? $question['dap_an_a'] : '',
                                                'B' => isset($question['dap_an_b']) ? $question['dap_an_b'] : '',
                                                'C' => isset($question['dap_an_c']) ? $question['dap_an_c'] : '',
                                                'D' => isset($question['dap_an_d']) ? $question['dap_an_d'] : '',
                                                'E' => isset($question['dap_an_e']) ? $question['dap_an_e'] : '',
                                                'F' => isset($question['dap_an_f']) ? $question['dap_an_f'] : '',
                                                'G' => isset($question['dap_an_g']) ? $question['dap_an_g'] : '',
                                                'H' => isset($question['dap_an_h']) ? $question['dap_an_h'] : ''
                                            ];
                                            
                                            // Lấy đáp án người dùng
                                            $userAnswer = isset($userAnswers[$question['id']]) ? $userAnswers[$question['id']] : null;
                                            
                                            // Nếu đáp án người dùng là chuỗi có dấu phẩy, chuyển thành mảng
                                            $userAnswerArray = is_string($userAnswer) && strpos($userAnswer, ',') !== false ? 
                                                            explode(',', $userAnswer) : (is_string($userAnswer) ? [$userAnswer] : []);
                                            
                                            foreach ($options as $key => $value) { 
                                                if (empty($value)) continue;
                                                
                                                $isUserAnswer = in_array($key, $userAnswerArray);
                                                
                                                $optionClass = 'form-check mb-2';
                                                
                                                // Chỉ đánh dấu đáp án của sinh viên, không hiển thị đáp án đúng
                                                if ($isUserAnswer) {
                                                    $optionClass .= ' bg-light';
                                                }
                                            ?>
                                                <div class="<?php echo $optionClass; ?>">
                                                    <input class="form-check-input" type="checkbox" disabled
                                                           <?php if ($isUserAnswer) echo 'checked'; ?>>
                                                    <label class="form-check-label">
                                                        <?php echo $key; ?>. <?php echo htmlspecialchars($value); ?>
                                                    </label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div> -->
                <?php elseif (!$isPast && !$isFuture): ?>
                    <!-- <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Bạn chưa làm bài kiểm tra này. Hãy nhấn "Bắt đầu làm bài" để làm bài.
                    </div> -->
                <?php endif; ?>
                
                <div class="mt-4">
                    <a href="index.php?controller=kiemtra&action=lichSu" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Đã xóa toàn bộ JavaScript xử lý làm bài thi
</script>
</body>
</html> 