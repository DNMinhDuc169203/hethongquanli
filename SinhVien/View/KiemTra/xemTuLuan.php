<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem bài thi tự luận</title>
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
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-edit"></i> <?php echo !empty($test['ten_bai_thi']) ? htmlspecialchars($test['ten_bai_thi']) : 'Bài kiểm tra tự luận'; ?>
                </h6>
                <div>
                    Môn học: <span class="font-weight-bold"><?php echo !empty($test['ten_mon_hoc']) ? htmlspecialchars($test['ten_mon_hoc']) : ''; ?></span>
                    &nbsp;|&nbsp;
                    Giáo viên: <span class="font-weight-bold"><?php echo !empty($test['ten_giao_vien']) ? htmlspecialchars($test['ten_giao_vien']) : ''; ?></span>
                </div>
            </div>
            <div class="card-body">
                <!-- Thông tin bài thi -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-clock"></i> Thời gian mở</span>
                                <span class="badge bg-primary">
                                    <?php echo !empty($test['thoi_gian_bat_dau']) ? date('H:i:s d/m/Y', strtotime($test['thoi_gian_bat_dau'])) : 'Chưa xác định'; ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-clock"></i> Thời gian đóng</span>
                                <span class="badge bg-danger">
                                    <?php echo !empty($test['thoi_gian_ket_thuc']) ? date('H:i:s d/m/Y', strtotime($test['thoi_gian_ket_thuc'])) : 'Chưa xác định'; ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-hourglass-half"></i> Thời gian làm bài</span>
                                <span class="badge bg-warning text-dark">
                                    <?php echo isset($test['thoi_gian_lam_bai']) ? $test['thoi_gian_lam_bai'] : '0'; ?> phút
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-redo"></i> Số lần làm bài</span>
                                <span class="badge bg-info text-white">
                                    <?php echo isset($test['so_lan_nop']) ? $test['so_lan_nop'] : '0'; ?>/<?php echo isset($test['so_lan_nop_toi_da']) ? $test['so_lan_nop_toi_da'] : '1'; ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-info-circle"></i> Mô tả bài thi
                            </div>
                            <div class="card-body">
                                <?php echo nl2br(htmlspecialchars(isset($test['mo_ta']) ? $test['mo_ta'] : 'Không có mô tả')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Nội dung đề bài / Câu hỏi -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-question-circle"></i> Nội dung đề bài
                    </div>
                    <div class="card-body">
                        <?php echo isset($test['noi_dung_de_bai']) ? $test['noi_dung_de_bai'] : '<p>Không có nội dung đề bài</p>'; ?>
                    </div>
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
                
                <?php
                // Kiểm tra trạng thái bài thi
                $now = new DateTime();
                $startTime = !empty($test['ngay_bat_dau']) ? new DateTime($test['ngay_bat_dau']) : $now;
                $endTime = !empty($test['ngay_ket_thuc']) ? new DateTime($test['ngay_ket_thuc']) : $now;
                $isPast = $now > $endTime;
                $isFuture = $now < $startTime;
                $isOngoing = !$isPast && !$isFuture;
                
                // Kiểm tra nếu đã nộp bài
                $hasSubmitted = isset($test['submission_id']) && !empty($test['submission_id']);
                ?>
                
                <!-- Trạng thái bài thi -->
                <?php if ($isFuture): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Bài kiểm tra này chưa mở. Vui lòng quay lại sau.
                    </div>
                <?php elseif ($isPast): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-lock"></i> Bài kiểm tra này đã kết thúc.
                    </div>
                <?php else: ?>
                    <!-- <div class="alert alert-success">
                        <i class="fas fa-unlock"></i> Bài kiểm tra đang mở và sẽ kết thúc vào: 
                        <strong><?php echo !empty($test['thoi_gian_ket_thuc']) ? date('H:i:s d/m/Y', strtotime($test['thoi_gian_ket_thuc'])) : 'Chưa xác định'; ?></strong>
                    </div> -->
                <?php endif; ?>
                
                <!-- Nếu đã nộp bài -->
                <?php if ($hasSubmitted): ?>
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-check-circle"></i> Bài làm của bạn
                        </div>
                        <div class="card-body">
                            <p>Thời gian nộp: <strong><?php echo date('H:i:s d/m/Y', strtotime($test['thoi_gian_nop'])); ?></strong></p>
                            
                            <?php if (isset($test['diem'])): ?>
                                <p>Điểm: <strong><?php echo $test['diem']; ?></strong></p>
                            <?php else: ?>
                                <p>Trạng thái: <span class="badge badge-warning">Chưa chấm điểm</span></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($test['noi_dung'])): ?>
                                <div class="card mb-3">
                                    <div class="card-header">Nội dung bài làm</div>
                                    <div class="card-body">
                                        <?php echo nl2br(htmlspecialchars($test['noi_dung'])); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($test['file_dinh_kem'])): ?>
                                <p>File đính kèm: <a href="<?php echo $test['file_dinh_kem']; ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> Tải xuống</a></p>
                            <?php endif; ?>
                            
                            <?php if ($isOngoing): ?>
                                <!-- <div class="mt-3">
                                    <a href="index.php?controller=kiemtra&action=xemTuLuan&id=<?php echo $test['id']; ?>" class="btn btn-warning">
                                        <i class="fas fa-redo"></i> Làm bài lại
                                    </a>
                                </div> -->
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Form nộp bài -->
                <?php if ($isOngoing && (!$hasSubmitted || ($hasSubmitted && $canSubmit))): ?>
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-paper-plane"></i> Nộp bài làm
                        </div>
                        <div class="card-body">
                            <form action="index.php?controller=kiemtra&action=nopTuLuan" method="POST" enctype="multipart/form-data" id="testForm">
                                <input type="hidden" name="test_id" value="<?php echo $test['id']; ?>">
                                <input type="hidden" name="is_submitting" value="1">
                                
                                <!-- Đếm ngược thời gian -->
                                <?php if (isset($test['thoi_gian_lam_bai']) && $test['thoi_gian_lam_bai'] > 0): ?>
                                    <div class="alert alert-warning text-center position-sticky" style="top: 15px; z-index: 1000;">
                                        <h5>
                                            <i class="fas fa-stopwatch"></i> Thời gian còn lại: 
                                            <span id="countdown" class="fw-bold" data-end-time="<?php echo date('Y-m-d H:i:s', strtotime('+' . $test['thoi_gian_lam_bai'] . ' minutes')); ?>" 
                                            data-total-minutes="<?php echo $test['thoi_gian_lam_bai']; ?>">
                                                <?php echo $test['thoi_gian_lam_bai']; ?>:00
                                            </span>
                                        </h5>
                                        <div class="progress mt-2" style="height: 5px;">
                                            <div id="time-progress" class="progress-bar bg-danger" role="progressbar" style="width: 100%"></div>
                                        </div>
                                        <small class="text-muted">Hết thời gian, bài thi sẽ tự động nộp</small>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="form-group">
                                    <label for="content"><i class="fas fa-file-alt"></i> Nội dung bài làm (tùy chọn)</label>
                                    <textarea name="content" id="content" class="form-control" rows="6" placeholder="Nhập nội dung bài làm hoặc ghi chú của bạn..."><?php echo isset($test['noi_dung']) ? htmlspecialchars($test['noi_dung']) : ''; ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="file_dinh_kem"><i class="fas fa-file-upload"></i> File bài làm</label>
                                    <input type="file" name="file_dinh_kem[]" id="file_dinh_kem" class="form-control" multiple>
                                    <small class="form-text text-muted">Chỉ chấp nhận file PDF, DOC, DOCX, JPG, JPEG, PNG, TXT. Kích thước tối đa 10MB mỗi file. Bạn có thể chọn nhiều file cùng lúc.</small>
                                    
                                    <?php if (!empty($test['file_dinh_kem'])): ?>
                                        <div class="mt-2">
                                            <p>File hiện tại:</p>
                                            <?php 
                                            if (is_array($test['file_dinh_kem'])) {
                                                foreach ($test['file_dinh_kem'] as $file) {
                                                    echo '<p><a href="' . $file . '" target="_blank">' . basename($file) . '</a></p>';
                                                }
                                            } else {
                                                echo '<p><a href="' . $test['file_dinh_kem'] . '" target="_blank">' . basename($test['file_dinh_kem']) . '</a></p>';
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="form-group text-center">
                                    <button type="button" id="saveProgressBtn" class="btn btn-warning mr-2">
                                        <i class="fas fa-save"></i> Lưu tiến độ
                                    </button>
                                    <button type="submit" id="submitTestBtn" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Nộp bài
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Nếu đã quá hạn và chưa nộp bài -->
                <?php if ($isPast && !$hasSubmitted): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> Bạn chưa nộp bài và đã hết thời gian làm bài.
                    </div>
                <?php endif; ?>
                
                <div class="mt-3">
                    <a href="index.php?controller=kiemtra&action=lichSu" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Countdown timer
        const countdownElement = document.getElementById('countdown');
        if (countdownElement) {
            const endTime = new Date(countdownElement.dataset.endTime).getTime();
            const totalMinutes = parseInt(countdownElement.dataset.totalMinutes);
            const totalSeconds = totalMinutes * 60;
            const startTime = new Date().getTime();
            const progressBar = document.getElementById('time-progress');
            
            function updateCountdown() {
                const now = new Date().getTime();
                const distance = endTime - now;
                
                if (distance < 0) {
                    countdownElement.innerHTML = "00:00";
                    // Hiển thị thông báo đang nộp bài
                    const submitNotice = document.createElement('div');
                    submitNotice.className = 'alert alert-info text-center mt-3';
                    submitNotice.innerHTML = '<i class="fas fa-paper-plane"></i> Đã hết thời gian làm bài, hệ thống đang nộp bài...';
                    document.getElementById('testForm').prepend(submitNotice);
                    
                    // Vô hiệu hóa các nút trong form
                    const buttons = document.querySelectorAll('#testForm button');
                    buttons.forEach(button => {
                        button.disabled = true;
                    });
                    
                    // Đảm bảo trường is_submitting được thiết lập khi tự động nộp bài
                    if (!document.querySelector('input[name="is_submitting"]')) {
                        const submittingInput = document.createElement('input');
                        submittingInput.type = 'hidden';
                        submittingInput.name = 'is_submitting';
                        submittingInput.value = '1';
                        document.getElementById('testForm').appendChild(submittingInput);
                    }
                    
                    document.getElementById('testForm').submit();
                    return;
                }
                
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                // Cập nhật progress bar
                if (progressBar) {
                    const timeRemaining = endTime - now;
                    const percentRemaining = (timeRemaining / (totalSeconds * 1000)) * 100;
                    progressBar.style.width = percentRemaining + '%';
                    
                    // Thay đổi màu khi thời gian còn lại ít
                    if (percentRemaining < 25) {
                        progressBar.classList.remove('bg-warning');
                        progressBar.classList.add('bg-danger');
                        
                        // Nhấp nháy khi còn ít thời gian
                        if (percentRemaining < 10) {
                            countdownElement.classList.toggle('text-danger');
                        }
                    } else if (percentRemaining < 50) {
                        progressBar.classList.remove('bg-danger');
                        progressBar.classList.add('bg-warning');
                    }
                }
                
                countdownElement.innerHTML = 
                    (minutes < 10 ? '0' : '') + minutes + ':' + 
                    (seconds < 10 ? '0' : '') + seconds;
            }
            
            updateCountdown();
            setInterval(updateCountdown, 1000);
        }
        
        // Save progress
        const saveProgressBtn = document.getElementById('saveProgressBtn');
        if (saveProgressBtn) {
            saveProgressBtn.addEventListener('click', function() {
                const form = document.getElementById('testForm');
                const formData = new FormData(form);
                formData.append('save_progress', '1');
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Tiến độ đã được lưu');
                    } else {
                        alert('Có lỗi xảy ra: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi lưu tiến độ');
                });
            });
        }
        
        // Confirmation before submit
        const submitTestBtn = document.getElementById('submitTestBtn');
        if (submitTestBtn) {
            submitTestBtn.addEventListener('click', function(e) {
                if (!confirm('Bạn có chắc chắn muốn nộp bài? Sau khi nộp bài, bạn sẽ không thể thay đổi câu trả lời.')) {
                    e.preventDefault();
                }
            });
        }
    });
</script>

</body>
</html> 