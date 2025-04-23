<!DOCTYPE html>
<?php
// Đặt múi giờ Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');
?>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Làm bài thi tự luận</title>
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
            border-radius: 8px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
        }
        .timer-card {
            position: sticky;
            top: 20px;
        }
        .timer-text {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc3545;
        }
        .action-buttons {
            position: sticky;
            bottom: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 -5px 10px rgba(0, 0, 0, 0.05);
        }
        .autosave-status {
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: 500;
            margin-right: 10px;
            display: inline-block;
            transition: all 0.3s ease;
            min-width: 150px;
            text-align: center;
        }
        .action-buttons {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 12px 20px;
            margin-top: 20px;
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
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt"></i> <?php echo !empty($test['ten_bai_thi']) ? htmlspecialchars($test['ten_bai_thi']) : 'Bài kiểm tra tự luận'; ?>
                        </h5>
                        <div>
                            <span class="badge bg-light text-dark">
                                Môn: <?php echo !empty($test['ten_mon_hoc']) ? htmlspecialchars($test['ten_mon_hoc']) : 'Chưa xác định'; ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Thông báo lỗi và cảnh báo -->
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['warning'])): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> <?php echo $_SESSION['warning']; unset($_SESSION['warning']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Mô tả bài thi -->
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-info-circle"></i> Mô tả bài thi
                            </div>
                            <div class="card-body">
                                <?php echo nl2br(htmlspecialchars(isset($test['mo_ta']) ? $test['mo_ta'] : 'Không có mô tả')); ?>
                            </div>
                        </div>
                        
                        <!-- Nội dung bài thi / Câu hỏi -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-question-circle"></i> Câu hỏi
                            </div>
                            <div class="card-body">
                                <?php echo isset($test['noi_dung']) ? $test['noi_dung'] : '<p>Không có nội dung câu hỏi</p>'; ?>
                            </div>
                        </div>
                        
                        <!-- Form làm bài -->
                        <form id="testForm" method="POST" action="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=nopTuLuan" enctype="multipart/form-data">
                            <input type="hidden" name="test_id" value="<?php echo $test['bai_thi_id']; ?>">
                            <input type="hidden" name="time_expired" id="timeExpired" value="0">
                            <input type="hidden" name="is_submitting" value="1">
                            
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <i class="fas fa-edit"></i> Làm bài
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-4">
                                        <label for="content" class="form-label fw-bold">Nội dung bài làm</label>
                                        <textarea name="content" id="content" class="form-control" rows="10" placeholder="Nhập nội dung bài làm của bạn..."><?php echo isset($test['saved_content']) ? htmlspecialchars($test['saved_content']) : ''; ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="file_dinh_kem" class="form-label fw-bold">File đính kèm</label>
                                        <input type="file" name="file_dinh_kem[]" id="file_dinh_kem" class="form-control" multiple>
                                        <small class="form-text text-muted">Chỉ chấp nhận file PDF, DOC, DOCX, JPG, JPEG, PNG, TXT. Kích thước tối đa 10MB mỗi file. Bạn có thể chọn nhiều file cùng lúc.</small>
                                        
                                        <?php if (isset($test['saved_files']) && !empty($test['saved_files'])): ?>
                                        <div class="mt-3">
                                            <p class="fw-bold">File đã lưu:</p>
                                            <ul class="list-group">
                                            <?php 
                                            if (is_array($test['saved_files'])) {
                                                foreach ($test['saved_files'] as $file) {
                                                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                                    echo basename($file);
                                                    echo '<a href="' . $file . '" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> Xem</a>';
                                                    echo '</li>';
                                                }
                                            } else {
                                                echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                                echo basename($test['saved_files']);
                                                echo '<a href="' . $test['saved_files'] . '" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> Xem</a>';
                                                echo '</li>';
                                            }
                                            ?>
                                            </ul>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                                
                            <!-- Nút điều hướng và nộp bài -->
                            <div class="action-buttons d-flex justify-content-between align-items-center">
                                <span class="autosave-status badge bg-warning" id="autosaveStatus" style="display: none;">
                                    <i class="fas fa-save"></i> Đang lưu...
                                </span>
                                <div>
                                    <button type="button" id="saveBtn" class="btn btn-outline-primary">
                                        <i class="fas fa-save"></i> Lưu bài làm
                                    </button>
                                    <button type="submit" id="submitBtn" class="btn btn-success ms-2">
                                        <i class="fas fa-paper-plane"></i> Nộp bài
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Thông tin bài thi và bộ đếm thời gian -->
                <div class="card timer-card mb-4">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-clock"></i> Thời gian còn lại
                    </div>
                    <div class="card-body text-center">
                        <div id="timer" class="timer-text">
                            <?php 
                            if (isset($test['thoi_gian_con_lai'])) {
                                echo gmdate('H:i:s', $test['thoi_gian_con_lai']);
                            } elseif (isset($test['thoi_gian_lam_bai']) && $test['thoi_gian_lam_bai'] > 0) {
                                echo gmdate('H:i:s', $test['thoi_gian_lam_bai'] * 60);
                            } else {
                                echo "Không giới hạn";
                            }
                            ?>
                        </div>
                        <div class="progress mt-2">
                            <div id="timerProgress" class="progress-bar bg-danger" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Thông tin bài thi -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-info-circle"></i> Thông tin bài thi
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-file-alt"></i> Tên bài thi:</span>
                                <span class="badge bg-primary"><?php echo !empty($test['ten_bai_thi']) ? htmlspecialchars($test['ten_bai_thi']) : 'Chưa xác định'; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-book"></i> Môn học:</span>
                                <span class="badge bg-info"><?php echo !empty($test['ten_mon_hoc']) ? htmlspecialchars($test['ten_mon_hoc']) : 'Chưa xác định'; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-user-tie"></i> Giáo viên:</span>
                                <span class="badge bg-secondary"><?php echo !empty($test['ten_giao_vien']) ? htmlspecialchars($test['ten_giao_vien']) : 'Chưa xác định'; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-clock"></i> Thời gian mở:</span>
                                <span class="badge bg-success"><?php echo !empty($test['ngay_bat_dau']) ? date('H:i:s d/m/Y', strtotime($test['ngay_bat_dau'])) : 'Chưa xác định'; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-clock"></i> Thời gian đóng:</span>
                                <span class="badge bg-warning text-dark"><?php echo !empty($test['ngay_ket_thuc']) ? date('H:i:s d/m/Y', strtotime($test['ngay_ket_thuc'])) : 'Chưa xác định'; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-redo"></i> Số lần nộp:</span>
                                <span class="badge bg-danger"><?php echo isset($test['so_lan_nop']) ? $test['so_lan_nop'] : '0'; ?>/<?php echo isset($test['so_lan_nop_toi_da']) ? $test['so_lan_nop_toi_da'] : '∞'; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Khởi tạo biến
    let timerInterval;
    let autoSaveInterval;
    let timeLeft = <?php echo isset($test['thoi_gian_con_lai']) ? $test['thoi_gian_con_lai'] : (isset($test['thoi_gian_lam_bai']) ? ($test['thoi_gian_lam_bai'] * 60) : 0); ?>;
    const totalTime = timeLeft;
    const hasTimeLimit = <?php echo isset($test['thoi_gian_lam_bai']) && $test['thoi_gian_lam_bai'] > 0 ? 'true' : 'false'; ?>;
    
    // Khởi tạo bộ đếm thời gian nếu có giới hạn
    if (hasTimeLimit) {
        function updateTimer() {
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                $('#timeExpired').val(1);
                $('#testForm').submit();
                return;
            }
            
            timeLeft--;
            const hours = Math.floor(timeLeft / 3600);
            const minutes = Math.floor((timeLeft % 3600) / 60);
            const seconds = timeLeft % 60;
            
            $('#timer').text(
                (hours < 10 ? '0' + hours : hours) + ':' +
                (minutes < 10 ? '0' + minutes : minutes) + ':' +
                (seconds < 10 ? '0' + seconds : seconds)
            );
            
            // Cập nhật thanh tiến trình
            const percentLeft = (timeLeft / totalTime) * 100;
            $('#timerProgress').css('width', percentLeft + '%');
            
            // Thay đổi màu dựa trên thời gian còn lại
            if (percentLeft < 25) {
                $('#timerProgress').removeClass('bg-warning bg-danger').addClass('bg-danger');
                $('#timer').addClass('text-danger');
            } else if (percentLeft < 50) {
                $('#timerProgress').removeClass('bg-danger bg-success').addClass('bg-warning');
            }
        }
        
        // Bắt đầu đếm giờ
        updateTimer();
        timerInterval = setInterval(updateTimer, 1000);
    }
    
    // Tự động lưu bài làm
    function autoSave() {
        // Lấy dữ liệu form và thêm tham số time_remaining
        const formData = new FormData(document.getElementById('testForm'));
        formData.append('time_remaining', timeLeft);
        formData.append('save_progress', '1');
        
        $.ajax({
            type: 'POST',
            url: '<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=saveTuLuanProgress',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(result) {
                if (result && result.success) {
                    $('#autosaveStatus').removeClass('bg-warning bg-danger').addClass('bg-success').html('<i class="fas fa-check"></i> Đã lưu');
                    setTimeout(function() {
                        $('#autosaveStatus').fadeOut();
                    }, 2000);
                } else {
                    const message = (result && result.message) ? result.message : 'Lỗi khi lưu';
                    $('#autosaveStatus').removeClass('bg-warning bg-success').addClass('bg-danger').fadeIn().html('<i class="fas fa-times"></i> ' + message);
                    console.error('Error saving progress:', message);
                }
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Lỗi kết nối';
                
                // Nếu có phản hồi từ server nhưng không phải JSON hợp lệ
                if (xhr.responseText) {
                    // Log lỗi chi tiết để debug
                    console.error('Server response:', xhr.responseText);
                    
                    try {
                        // Thử phân tích lại JSON
                        const result = JSON.parse(xhr.responseText);
                        if (result && result.message) {
                            errorMsg = result.message;
                        }
                    } catch (e) {
                        // Nếu không phải JSON, hiển thị lỗi chung
                        errorMsg = 'Lỗi phân tích phản hồi';
                        console.error('JSON parse error:', e);
                    }
                }
                
                $('#autosaveStatus').removeClass('bg-warning bg-success').addClass('bg-danger').fadeIn().html('<i class="fas fa-times"></i> ' + errorMsg);
                console.error('AJAX error:', status, error);
                
                setTimeout(function() {
                    $('#autosaveStatus').fadeOut();
                }, 2000);
            }
        });
    }
    
    // Lưu bài làm với trạng thái
    function saveWithStatus(status) {
        const formData = new FormData(document.getElementById('testForm'));
        formData.append('time_remaining', timeLeft);
        formData.append('status', status);
        formData.append('save_progress', '1');
        
        if (status === 'tam_ngung' && navigator.sendBeacon) {
            // Sử dụng sendBeacon để đảm bảo request được gửi khi rời trang
            const data = new FormData();
            for (const [key, value] of formData.entries()) {
                data.append(key, value);
            }
            
            navigator.sendBeacon(
                '<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=saveTuLuanProgress', 
                data
            );
        } else {
            $.ajax({
                type: 'POST',
                url: '<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=saveTuLuanProgress',
                data: formData,
                contentType: false,
                processData: false,
                async: (status !== 'tam_ngung')
            });
        }
    }
    
    // Bắt đầu tự động lưu mỗi 30 giây
    autoSaveInterval = setInterval(autoSave, 30000);
    
    // Lưu bài làm khi nhấn nút Lưu
    $('#saveBtn').click(function() {
        // Hiển thị thông báo đang lưu
        $('#autosaveStatus').fadeIn().removeClass('bg-success bg-danger').addClass('bg-warning').html('<i class="fas fa-sync fa-spin"></i> Đang lưu...');
        
        // Lấy dữ liệu form và lưu
        const formData = new FormData(document.getElementById('testForm'));
        formData.append('time_remaining', timeLeft);
        formData.append('save_progress', '1');
        
        $.ajax({
            type: 'POST',
            url: '<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=saveTuLuanProgress',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(result) {
                if (result && result.success) {
                    $('#autosaveStatus').removeClass('bg-warning bg-danger').addClass('bg-success').html('<i class="fas fa-check"></i> Đã lưu thành công');
                } else {
                    const message = (result && result.message) ? result.message : 'Lỗi khi lưu';
                    $('#autosaveStatus').removeClass('bg-warning bg-success').addClass('bg-danger').html('<i class="fas fa-times"></i> ' + message);
                    console.error('Error saving progress:', message);
                }
                
                // Ẩn thông báo sau 2 giây
                setTimeout(function() {
                    $('#autosaveStatus').fadeOut();
                }, 2000);
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Lỗi kết nối';
                
                // Nếu có phản hồi từ server nhưng không phải JSON hợp lệ
                if (xhr.responseText) {
                    // Log lỗi chi tiết để debug
                    console.error('Server response:', xhr.responseText);
                    
                    try {
                        // Thử phân tích lại JSON
                        const result = JSON.parse(xhr.responseText);
                        if (result && result.message) {
                            errorMsg = result.message;
                        }
                    } catch (e) {
                        // Nếu không phải JSON, hiển thị lỗi chung
                        errorMsg = 'Lỗi phân tích phản hồi';
                        console.error('JSON parse error:', e);
                    }
                }
                
                $('#autosaveStatus').removeClass('bg-warning bg-success').addClass('bg-danger').html('<i class="fas fa-times"></i> ' + errorMsg);
                console.error('AJAX error:', status, error);
                
                // Ẩn thông báo lỗi sau 2 giây
                setTimeout(function() {
                    $('#autosaveStatus').fadeOut();
                }, 2000);
            }
        });
    });
    
    // Xác nhận trước khi nộp bài
    $('#submitBtn').click(function(e) {
        if (!confirm('Bạn có chắc chắn muốn nộp bài không?')) {
            e.preventDefault();
        }
    });
    
    // Thêm sự kiện beforeunload
    window.addEventListener('beforeunload', function(e) {
        // Lưu tiến độ trước khi thoát trang với trạng thái 'tam_ngung'
        saveWithStatus('tam_ngung');
        
        // Hiển thị thông báo xác nhận khi người dùng thoát trang
        const message = 'Bạn có chắc chắn muốn rời khỏi trang? Dữ liệu chưa nộp có thể bị mất.';
        e.returnValue = message;
        return message;
    });
    
    // Lưu tiến độ khi tab mất focus hoặc cửa sổ trình duyệt bị thu nhỏ
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'hidden') {
            saveWithStatus('tam_ngung');
        } else if (document.visibilityState === 'visible') {
            saveWithStatus('dang_lam');
        }
    });
});
</script>
</body>
</html> 