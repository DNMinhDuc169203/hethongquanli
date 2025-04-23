<!DOCTYPE html>
<?php
// Đặt múi giờ Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');
?>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Làm bài thi trắc nghiệm</title>
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
        .question-card {
            border-left: 4px solid #3a7bd5;
        }
        .question-header {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .option-label {
            display: block;
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #e0e0e0;
            cursor: pointer;
            transition: all 0.2s;
        }
        .option-label:hover {
            background-color: #f5f5f5;
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
        .answer-status {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;
        }
        .question-number {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            font-size: 0.9rem;
            cursor: pointer;
        }
        .answered {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }
        .not-answered {
            background-color: #dc3545;
            color: white;
            border-color: #dc3545;
        }
        .checkbox-custom, .radio-custom {
            opacity: 0;
            position: absolute;
        }
        .checkbox-custom-label, .radio-custom-label {
            position: relative;
            padding-left: 30px;
            cursor: pointer;
            display: block;
            line-height: 24px;
        }
        .checkbox-custom-label:before, .radio-custom-label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 22px;
            height: 22px;
            border: 2px solid #ccc;
            background-color: #fff;
        }
        .radio-custom-label:before {
            border-radius: 50%;
        }
        .checkbox-custom:checked + .checkbox-custom-label:before {
            background-color: #3a7bd5;
            border-color: #3a7bd5;
        }
        .radio-custom:checked + .radio-custom-label:before {
            background-color: #3a7bd5;
            border-color: #3a7bd5;
        }
        .checkbox-custom-label:after, .radio-custom-label:after {
            content: '';
            position: absolute;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .checkbox-custom:checked + .checkbox-custom-label:after {
            opacity: 1;
            left: 8px;
            top: 4px;
            width: 6px;
            height: 12px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        .radio-custom:checked + .radio-custom-label:after {
            opacity: 1;
            left: 7px;
            top: 7px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: white;
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
                            <i class="fas fa-file-alt"></i> <?php echo !empty($test['tieu_de']) ? htmlspecialchars($test['tieu_de']) : 'Bài kiểm tra'; ?>
                        </h5>
                        <div>
                            <span class="badge bg-light text-dark">
                                Môn: <?php echo !empty($test['ten_mon']) ? htmlspecialchars($test['ten_mon']) : 'Chưa xác định'; ?>
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
                        
                        <!-- Form làm bài -->
                        <form id="testForm" method="POST" action="<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=nopTracNghiem">
                            <input type="hidden" name="test_id" value="<?php echo $test['id']; ?>">
                            <input type="hidden" name="time_expired" id="timeExpired" value="0">
                            
                            <!-- Danh sách câu hỏi -->
                            <?php if (!empty($questions)): ?>
                                <div class="questions-container">
                                    <?php foreach ($questions as $index => $question): ?>
                                        <div class="card question-card mb-4" id="question-<?php echo $question['id']; ?>">
                                            <div class="card-body">
                                                <h5 class="question-header">
                                                    <span class="badge bg-primary me-2">Câu <?php echo $index + 1; ?></span>
                                                    <?php echo htmlspecialchars($question['noi_dung']); ?>
                                                    <?php if (!empty($question['tieu_de'])): ?>
                                                        <small class="d-block text-muted mt-1"><?php echo htmlspecialchars($question['tieu_de']); ?></small>
                                                    <?php endif; ?>
                                                    <?php if (!empty($question['ten_chu_de'])): ?>
                                                        <span class="badge bg-info text-white"><?php echo htmlspecialchars($question['ten_chu_de']); ?></span>
                                                    <?php endif; ?>
                                                </h5>
                                                
                                                <div class="options mt-3">
                                                    <?php
                                                    // Xác định loại câu hỏi (nhiều đáp án hay một đáp án)
                                                    $isMultipleChoice = isset($question['nhieu_dap_an']) && $question['nhieu_dap_an'] === true;
                                                    $inputType = $isMultipleChoice ? 'checkbox' : 'radio';
                                                    $savedAnswerKey = 'answers[' . $question['id'] . ']';
                                                    $savedAnswer = isset($savedAnswers[$question['id']]) ? $savedAnswers[$question['id']] : [];
                                                    
                                                    // Chuyển đổi savedAnswer thành mảng nếu là chuỗi
                                                    if (is_string($savedAnswer) && strpos($savedAnswer, ',') !== false) {
                                                        $savedAnswer = explode(',', $savedAnswer);
                                                    } elseif (is_string($savedAnswer) && !empty($savedAnswer)) {
                                                        $savedAnswer = [$savedAnswer];
                                                    } elseif (!is_array($savedAnswer)) {
                                                        $savedAnswer = [];
                                                    }
                                                    
                                                    // Danh sách các đáp án có thể có
                                                    $options = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
                                                    
                                                    foreach ($options as $option):
                                                        $optionKey = 'dap_an_' . $option;
                                                        $optionValue = isset($question[$optionKey]) ? $question[$optionKey] : null;
                                                        
                                                        // Chỉ hiển thị đáp án nếu có nội dung
                                                        if ($optionValue):
                                                            $optionId = 'question_' . $question['id'] . '_option_' . $option;
                                                            $checked = in_array(strtoupper($option), $savedAnswer) ? 'checked' : '';
                                                            $name = $isMultipleChoice ? "answers[{$question['id']}][]" : "answers[{$question['id']}]";
                                                    ?>
                                                    <div class="option-item mb-2">
                                                        <input type="<?php echo $inputType; ?>" 
                                                               id="<?php echo $optionId; ?>" 
                                                               name="<?php echo $name; ?>" 
                                                               value="<?php echo strtoupper($option); ?>" 
                                                               class="<?php echo $inputType; ?>-custom"
                                                               <?php echo $checked; ?>>
                                                        <label for="<?php echo $optionId; ?>" class="<?php echo $inputType; ?>-custom-label">
                                                            <?php echo htmlspecialchars($optionValue); ?>
                                                        </label>
                                                    </div>
                                                    <?php 
                                                        endif;
                                                    endforeach; 
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
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
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Không có câu hỏi nào trong bài kiểm tra này.
                                </div>
                            <?php endif; ?>
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
                            } elseif (isset($test['thoi_gian_lam']) && $test['thoi_gian_lam'] > 0) {
                                echo gmdate('H:i:s', $test['thoi_gian_lam'] * 60);
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
                                <span class="badge bg-primary"><?php echo !empty($test['tieu_de']) ? htmlspecialchars($test['tieu_de']) : 'Chưa xác định'; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-book"></i> Môn học:</span>
                                <span class="badge bg-info"><?php echo !empty($test['ten_mon']) ? htmlspecialchars($test['ten_mon']) : 'Chưa xác định'; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-user-tie"></i> Giáo viên:</span>
                                <span class="badge bg-secondary"><?php echo !empty($test['ten_giao_vien']) ? htmlspecialchars($test['ten_giao_vien']) : 'Chưa xác định'; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-question-circle"></i> Số câu hỏi:</span>
                                <span class="badge bg-success"><?php echo !empty($test['so_cau_hoi']) ? $test['so_cau_hoi'] : '0'; ?> câu</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-stopwatch"></i> Thời gian:</span>
                                <span class="badge bg-warning text-dark"><?php echo isset($test['thoi_gian_lam']) ? $test['thoi_gian_lam'] . ' phút' : 'Không giới hạn'; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-redo"></i> Số lần làm:</span>
                                <span class="badge bg-danger"><?php echo isset($test['so_lan_da_lam']) ? $test['so_lan_da_lam'] : '0'; ?>/<?php echo isset($test['so_lan_lam']) ? $test['so_lan_lam'] : '∞'; ?></span>
                            </li>
                        </ul>
                        <!-- <div class="alert alert-info mt-3 mb-0">
                            <i class="fas fa-save"></i> Hệ thống tự động lưu bài làm mỗi 3 giây. Bạn có thể nhấn nút "Lưu bài làm" để lưu ngay.
                        </div> -->
                    </div>
                </div>
                
                <!-- Trạng thái câu trả lời -->
                <?php if (!empty($questions)): ?>
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-tasks"></i> Trạng thái làm bài
                    </div>
                    <div class="card-body">
                        <div class="answer-status" id="answerStatus">
                            <?php foreach ($questions as $index => $question): ?>
                                <a href="#question-<?php echo $question['id']; ?>" class="question-number" data-question-id="<?php echo $question['id']; ?>">
                                    <?php echo $index + 1; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <span><i class="fas fa-circle text-success"></i> Đã trả lời</span>
                            <span><i class="fas fa-circle text-danger"></i> Chưa trả lời</span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
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
    let timeLeft = <?php echo isset($test['thoi_gian_con_lai']) ? $test['thoi_gian_con_lai'] : (isset($test['thoi_gian_lam']) ? ($test['thoi_gian_lam'] * 60) : 0); ?>;
    const totalTime = timeLeft;
    const hasTimeLimit = <?php echo isset($test['thoi_gian_lam']) && $test['thoi_gian_lam'] > 0 ? 'true' : 'false'; ?>;
    
    // Cập nhật trạng thái câu trả lời
    function updateAnswerStatus() {
        $('.question-number').each(function() {
            const questionId = $(this).data('question-id');
            const hasAnswer = $('input[name^="answers[' + questionId + ']"]:checked').length > 0;
            
            if (hasAnswer) {
                $(this).addClass('answered').removeClass('not-answered');
            } else {
                $(this).addClass('not-answered').removeClass('answered');
            }
        });
    }
    
    // Khởi tạo trạng thái ban đầu
    updateAnswerStatus();
    
    // Cập nhật khi có thay đổi
    $('input[type="radio"], input[type="checkbox"]').change(function() {
        updateAnswerStatus();
    });
    
    // Cuộn đến câu hỏi khi click vào số
    $('.question-number').click(function(e) {
        e.preventDefault();
        const target = $(this).attr('href');
        $('html, body').animate({
            scrollTop: $(target).offset().top - 100
        }, 500);
    });
    
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
        // Hiển thị thông báo đang lưu
        //$('#autosaveStatus').fadeIn().html('<i class="fas fa-sync fa-spin"></i> Đang lưu...');
        
        // Lấy dữ liệu form và thêm tham số time_remaining
        const formData = $('#testForm').serialize() + '&time_remaining=' + timeLeft;
        
        $.ajax({
            type: 'POST',
            url: '<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=saveTracNghiemProgress',
            data: formData,
            dataType: 'json',
            success: function(result) {
                if (result && result.success) {
                    //$('#autosaveStatus').removeClass('bg-warning bg-danger').addClass('bg-success').html('<i class="fas fa-check"></i> Đã lưu');
                } else {
                    const message = (result && result.message) ? result.message : 'Lỗi khi lưu';
                    $('#autosaveStatus').removeClass('bg-warning bg-success').addClass('bg-danger').fadeIn().html('<i class="fas fa-times"></i> ' + message);
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
    }
    
    // Lưu bài làm với trạng thái
    function saveWithStatus(status) {
        // Lấy dữ liệu form và thêm các tham số cần thiết
        const formData = $('#testForm').serialize() + '&time_remaining=' + timeLeft + '&status=' + status;
        
        // Nếu đang rời khỏi trang, sử dụng sendBeacon để đảm bảo request được gửi
        if (status === 'tam_ngung' && navigator.sendBeacon) {
            const data = new FormData();
            const formDataObj = new URLSearchParams(formData);
            
            // Chuyển đổi formData thành FormData để sử dụng với sendBeacon
            for (const [key, value] of formDataObj.entries()) {
                data.append(key, value);
            }
            
            navigator.sendBeacon(
                '<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=saveTracNghiemProgress', 
                data
            );
        } else {
            // Nếu không phải đang rời khỏi trang hoặc trình duyệt không hỗ trợ sendBeacon
            // thì sử dụng Ajax thông thường
            $.ajax({
                type: 'POST',
                url: '<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=saveTracNghiemProgress',
                data: formData,
                dataType: 'json',
                async: (status !== 'tam_ngung'), // Synchronous nếu đang thoát trang
                success: function(result) {
                    if (result && result.success) {
                        // Lưu thành công - không cần hiển thị gì khi đang thoát trang
                        console.log('Status saved successfully:', status);
                    } else {
                        console.error('Error saving status:', (result && result.message) ? result.message : 'Unknown error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error when saving status:', status, error);
                    
                    // Log phản hồi từ server để debug
                    if (xhr.responseText) {
                        console.error('Server response:', xhr.responseText);
                    }
                }
            });
        }
    }
    
    // Bắt đầu tự động lưu mỗi 3 giây
    autoSaveInterval = setInterval(autoSave, 3000);
    
    // Lưu bài làm khi nhấn nút Lưu
    $('#saveBtn').click(function() {
        // Hiển thị thông báo đang lưu
        $('#autosaveStatus').fadeIn().removeClass('bg-success bg-danger').addClass('bg-warning').html('<i class="fas fa-sync fa-spin"></i> Đang lưu...');
        
        // Lấy dữ liệu form và thêm tham số time_remaining
        const formData = $('#testForm').serialize() + '&time_remaining=' + timeLeft;
        
        $.ajax({
            type: 'POST',
            url: '<?php echo BASE_URL; ?>/index.php?controller=kiemtra&action=saveTracNghiemProgress',
            data: formData,
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
    
    // Khôi phục các câu trả lời đã lưu nếu có
    <?php if (isset($test['saved_answers']) && !empty($test['saved_answers'])): ?>
    const savedAnswers = <?php echo json_encode($test['saved_answers']); ?>;
    for (const questionId in savedAnswers) {
        if (Array.isArray(savedAnswers[questionId])) {
            // Nhiều đáp án
            savedAnswers[questionId].forEach(function(answer) {
                $('input[name="answers[' + questionId + '][]"][value="' + answer + '"]').prop('checked', true);
            });
        } else {
            // Một đáp án
            $('input[name="answers[' + questionId + ']"][value="' + savedAnswers[questionId] + '"]').prop('checked', true);
        }
    }
    // Cập nhật trạng thái câu trả lời
    updateAnswerStatus();
    <?php endif; ?>
});
</script>
</body>
</html> 