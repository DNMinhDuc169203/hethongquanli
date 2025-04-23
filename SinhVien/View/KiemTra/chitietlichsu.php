<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết bài thi - <?php echo htmlspecialchars($test['tieu_de'] ?? ''); ?></title>
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
        .correct-answer {
            color: #198754;
            font-weight: bold;
        }
        .incorrect-answer {
            color: #dc3545;
            font-weight: bold;
        }
        .badge-topic {
            background-color: #0dcaf0;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
        }
        .question-card {
            border-left: 5px solid #6c757d;
            transition: all 0.2s;
        }
        .question-card.correct {
            border-left: 5px solid #198754;
        }
        .question-card.incorrect {
            border-left: 5px solid #dc3545;
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
            <h2><i class="fas fa-history me-2"></i>Chi tiết bài thi</h2>
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
        
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary text-white">
                <h5 class="m-0 font-weight-bold">
                    <i class="fas fa-history"></i> Chi tiết bài thi: <?php echo htmlspecialchars($test['tieu_de'] ?? ''); ?>
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
                                        <th>Số câu hỏi:</th>
                                        <td><?php echo count($questions ?? []); ?> câu</td>
                                    </tr>
                                    <tr>
                                        <th>Ngày làm:</th>
                                        <td><?php echo isset($baiLam['thoi_gian_nop']) ? date('H:i:s d/m/Y', strtotime($baiLam['thoi_gian_nop'])) : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Lần làm thứ:</th>
                                        <td><?php echo $baiLam['lan_thu'] ?? '1'; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-award"></i> Kết quả</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <h1 class="display-1 <?php echo isset($baiLam['diem']) && $baiLam['diem'] >= 5 ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo isset($baiLam['diem']) ? number_format($baiLam['diem'], 1) : '0'; ?>
                                    </h1>
                                    <p class="lead">Điểm số (thang điểm 10)</p>
                                </div>
                                
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="p-3 border rounded bg-light">
                                            <h3 class="text-success"><?php echo $soCauDung ?? 0; ?></h3>
                                            <p class="mb-0">Câu đúng</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3 border rounded bg-light">
                                            <h3 class="text-danger"><?php echo count($questions ?? []) - ($soCauDung ?? 0); ?></h3>
                                            <p class="mb-0">Câu sai</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Chi tiết câu hỏi và đáp án -->
                <h5 class="mb-3"><i class="fas fa-list-ol"></i> Chi tiết bài làm</h5>
                
                <?php if (!empty($questions)): ?>
                    <?php foreach ($questions as $index => $question): 
                        // Xác định người dùng đã trả lời đúng hay sai
                        $userAnswer = isset($userAnswers[$question['id']]) ? $userAnswers[$question['id']] : '';
                        $correctAnswer = isset($question['dap_an_dung']) ? $question['dap_an_dung'] : '';
                        $correctAnswerArray = isset($question['dap_an_dung_mang']) ? $question['dap_an_dung_mang'] : [$correctAnswer];
                        
                        // Chuyển đáp án người dùng thành mảng nếu là chuỗi có dấu phẩy
                        $userAnswerArray = is_string($userAnswer) && strpos($userAnswer, ',') !== false ? 
                                        explode(',', $userAnswer) : (is_string($userAnswer) ? [$userAnswer] : $userAnswer);
                        
                        // Kiểm tra câu trả lời đúng hay sai
                        $isCorrect = false;
                        if (is_array($userAnswerArray) && is_array($correctAnswerArray)) {
                            sort($userAnswerArray);
                            sort($correctAnswerArray);
                            $isCorrect = $userAnswerArray == $correctAnswerArray;
                        }
                        
                        $cardClass = $isCorrect ? 'correct' : 'incorrect';
                    ?>
                        <div class="card mb-3 question-card <?php echo $cardClass; ?>">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    Câu <?php echo $index + 1; ?>: <?php echo htmlspecialchars($question['noi_dung'] ?? ''); ?>
                                </h6>
                                <?php if (!empty($question['ten_chu_de'])): ?>
                                    <span class="badge-topic">
                                        <i class="fas fa-tag"></i> <?php echo htmlspecialchars($question['ten_chu_de']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
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
                                
                                foreach ($options as $key => $value): 
                                    if (empty($value)) continue;
                                    
                                    $isUserChosen = is_array($userAnswerArray) && in_array($key, $userAnswerArray);
                                    $isCorrectOption = in_array($key, $correctAnswerArray);
                                    
                                    $optionClass = '';
                                    if ($isUserChosen && $isCorrectOption) {
                                        $optionClass = 'correct-answer';
                                    } elseif ($isUserChosen && !$isCorrectOption) {
                                        $optionClass = 'incorrect-answer';
                                    } elseif (!$isUserChosen && $isCorrectOption) {
                                        $optionClass = 'correct-answer';
                                    }
                                ?>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" disabled <?php echo $isUserChosen ? 'checked' : ''; ?>>
                                        <label class="form-check-label <?php echo $optionClass; ?>">
                                            <?php echo $key; ?>. <?php echo htmlspecialchars($value); ?>
                                            <?php if ($isCorrectOption): ?>
                                                <i class="fas fa-check-circle text-success ms-1"></i>
                                            <?php elseif ($isUserChosen): ?>
                                                <i class="fas fa-times-circle text-danger ms-1"></i>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                                
                                <?php if (!empty($question['giai_thich'])): ?>
                                    <div class="mt-3 p-2 bg-light border rounded">
                                        <strong><i class="fas fa-info-circle"></i> Giải thích:</strong>
                                        <div><?php echo nl2br(htmlspecialchars($question['giai_thich'])); ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Không có dữ liệu chi tiết về bài làm này.
                    </div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 