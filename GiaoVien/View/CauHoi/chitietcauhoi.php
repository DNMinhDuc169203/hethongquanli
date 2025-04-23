<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết câu hỏi trắc nghiệm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        .question-detail {
            background-color: #e0f7ea;
            padding: 20px;
            border-radius: 5px;
        }
        .menu-item .btn-danger {
            text-align: left;
            margin-top: 20px;
        }
        .menu-item .btn-danger i {
            margin-right: 10px;
        }
        .question-block {
            background-color: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
        }
        .question-header {
            margin-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .answer-option {
            margin-bottom: 10px;
            padding: 8px 15px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }
        .answer-correct {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .question-content {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 15px;
        }
        /* CSS cho việc ẩn/hiện đáp án */
        .answers-hidden .answer-option {
            background-color: #f8f9fa !important;
            border-color: #dee2e6 !important;
        }
        .answers-hidden .badge {
            display: none;
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
                <h3>Quản lý câu hỏi</h3>
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
                    <a href="index.php?controller=auth&action=logout" class="btn btn-danger w-100">
                         Đăng xuất
                    </a>
                </div>
            </div>
            
            <!-- Main content -->
            <div class="col-md-9 content">
                <?php if ($monHoc && $chuDe): ?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?controller=cauhoi">Danh sách môn học</a></li>
                        <li class="breadcrumb-item">
                            <a href="index.php?controller=cauhoi&action=viewBySubject&id=<?= $monHoc['id'] ?>">
                                Chủ đề môn <?= htmlspecialchars($monHoc['ten_mon']) ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="index.php?controller=cauhoi&action=viewByTopic&id=<?= $chuDe['id'] ?>">
                                Câu hỏi chủ đề <?= htmlspecialchars($chuDe['ten_chu_de']) ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết câu hỏi</li>
                    </ol>
                </nav>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Chi tiết câu hỏi trắc nghiệm</h2>
                    <div>
                        <?php if ($chuDe): ?>
                        <a href="index.php?controller=cauhoi&action=viewByTopic&id=<?= $chuDe['id'] ?>" class="btn btn-secondary">Quay lại</a>
                        <?php else: ?>
                        <a href="index.php?controller=cauhoi" class="btn btn-secondary">Quay lại</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Thông tin chung</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Tiêu đề chung:</strong> <?php echo $cauHoi['chu_de']; ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($cauHoi['ngay_tao'])); ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Ngày cập nhật:</strong> <?php echo date('d/m/Y H:i', strtotime($cauHoi['ngay_cap_nhat'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body question-detail">
                        <?php foreach ($danhSachCauHoi as $index => $cauhoi): ?>
                            <div class="question-block">
                                <div class="question-header d-flex justify-content-between align-items-center">
                                    <h4>Câu hỏi <?php echo $cauhoi['question_number']; ?></h4>
                                    <button type="button" class="btn btn-sm btn-outline-primary toggle-answers" data-show="false">
                                        <i class="fas fa-eye-slash"></i> Hiện đáp án
                                    </button>
                                </div>
                                
                                <div class="question-content">
                                    <h4>Nội Dung</h4><?php echo $cauhoi['noi_dung']; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <p>
                                        <strong>Loại câu hỏi:</strong> 
                                        <?php echo $cauhoi['multiple_correct'] ? 'Nhiều đáp án đúng' : 'Một đáp án đúng'; ?>
                                    </p>
                                </div>
                                
                                <div class="answers-container answers-hidden">
                                    <h5>Các đáp án:</h5>
                                    <?php foreach ($cauhoi['dap_an'] as $indexDA => $dapAn): ?>
                                        <div class="answer-option <?php echo $dapAn['is_correct'] ? 'answer-correct' : ''; ?>">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <?php if ($dapAn['is_correct']): ?>
                                                        <span class="badge bg-success">Đáp án đúng</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Đáp án sai</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div><?php echo $dapAn['noi_dung']; ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý sự kiện hiển thị/ẩn đáp án
            const toggleButtons = document.querySelectorAll('.toggle-answers');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const questionBlock = this.closest('.question-block');
                    const answersContainer = questionBlock.querySelector('.answers-container');
                    const isCurrentlyShown = this.getAttribute('data-show') === 'true';
                    
                    if (isCurrentlyShown) {
                        // Ẩn đáp án
                        answersContainer.classList.add('answers-hidden');
                        this.innerHTML = '<i class="fas fa-eye-slash"></i> Hiện đáp án';
                        this.setAttribute('data-show', 'false');
                    } else {
                        // Hiện đáp án
                        answersContainer.classList.remove('answers-hidden');
                        this.innerHTML = '<i class="fas fa-eye"></i> Ẩn đáp án';
                        this.setAttribute('data-show', 'true');
                    }
                });
            });
        });
    </script>
</body>
</html>