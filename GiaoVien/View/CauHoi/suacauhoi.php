<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa câu hỏi trắc nghiệm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
        .question-form {
            background-color: #e0f7ea;
            padding: 20px;
            border-radius: 5px;
        }
        .answer-option {
            margin-bottom: 10px;
        }
        .menu-item .btn-danger {
            text-align: left;
            margin-top: 20px;
        }
        .menu-item .btn-danger i {
            margin-right: 10px;
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
                        <li class="breadcrumb-item active" aria-current="page">Sửa câu hỏi</li>
                    </ol>
                </nav>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Sửa câu hỏi trắc nghiệm</h2>
                    <?php if ($chuDe): ?>
                    <a href="index.php?controller=cauhoi&action=viewByTopic&id=<?= $chuDe['id'] ?>" class="btn btn-secondary">Quay lại</a>
                    <?php else: ?>
                    <a href="index.php?controller=cauhoi&action=view&id=<?php echo $cauHoi['id']; ?>" class="btn btn-secondary">Quay lại</a>
                    <?php endif; ?>
                </div>
                
                <div class="card">
                    <div class="card-body question-form">
                        <form action="index.php?controller=cauhoi&action=edit&id=<?php echo $cauHoi['id']; ?>" method="POST">
                            <div class="mb-3">
                                <label for="tieu_de" class="form-label">Tiêu đề chung</label>
                                <input type="text" class="form-control" id="tieu_de" name="tieu_de" value="<?php echo htmlspecialchars($cauHoi['tieu_de']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="noi_dung" class="form-label">Nội dung câu hỏi</label>
                                <input type="text" class="form-control" id="noi_dung" name="noi_dung" value="<?php echo htmlspecialchars($cauHoi['noi_dung']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="multiple_correct" name="multiple_correct" value="1" <?php echo (count(array_filter($dapAn, function($da) { return $da['dung_hay_sai'] == 1; })) > 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="multiple_correct">
                                        Cho phép nhiều đáp án đúng
                                    </label>
                                </div>
                            </div>
                            
                            <div id="dap-an-container">
                                <h5 class="mb-3">Các đáp án:</h5>
                                
                                <?php foreach ($dapAn as $index => $da): ?>
                                <div class="answer-option">
                                    <div class="input-group mb-2">
                                        <div class="input-group-text">
                                            <input class="form-check-input answer-correct" type="<?php echo (count(array_filter($dapAn, function($d) { return $d['dung_hay_sai'] == 1; })) > 1) ? 'checkbox' : 'radio'; ?>" name="dap_an_dung[]" value="<?php echo $index; ?>" <?php echo $da['dung_hay_sai'] ? 'checked' : ''; ?>>
                                        </div>
                                        <input type="text" class="form-control" name="dap_an[<?php echo $index; ?>]" value="<?php echo htmlspecialchars($da['dap_an_cua_trac_nghiem']); ?>" required>
                                        <button type="button" class="btn btn-outline-danger remove-answer">X</button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                
                            </div>
                            
                            <div class="mt-3 mb-4">
                                <button type="button" id="add-answer" class="btn btn-info">Thêm đáp án</button>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý chuyển đổi giữa radio và checkbox
            const multipleCorrectCheckbox = document.getElementById('multiple_correct');
            
            multipleCorrectCheckbox.addEventListener('change', function() {
                const inputType = this.checked ? 'checkbox' : 'radio';
                const answerInputs = document.querySelectorAll('.answer-correct');
                
                // Thay đổi loại input
                answerInputs.forEach((input, index) => {
                    const newInput = document.createElement('input');
                    newInput.className = 'form-check-input answer-correct';
                    newInput.type = inputType;
                    newInput.name = 'dap_an_dung[]';
                    newInput.value = index;
                    
                    // Giữ lại trạng thái checked nếu có
                    if (input.checked) {
                        newInput.checked = true;
                    }
                    
                    // Thay thế input cũ bằng input mới
                    input.parentNode.replaceChild(newInput, input);
                });
            });
            
            // Thêm đáp án mới
            document.getElementById('add-answer').addEventListener('click', function() {
                const container = document.getElementById('dap-an-container');
                const answerCount = document.querySelectorAll('.answer-option').length;
                const multipleCorrect = document.getElementById('multiple_correct').checked;
                const inputType = multipleCorrect ? 'checkbox' : 'radio';
                
                const newAnswer = document.createElement('div');
                newAnswer.className = 'answer-option';
                newAnswer.innerHTML = `
                    <div class="input-group mb-2">
                        <div class="input-group-text">
                            <input class="form-check-input answer-correct" type="${inputType}" name="dap_an_dung[]" value="${answerCount}">
                        </div>
                        <input type="text" class="form-control" name="dap_an[${answerCount}]" required>
                        <button type="button" class="btn btn-outline-danger remove-answer">X</button>
                    </div>
                `;
                
                container.appendChild(newAnswer);
                
                // Xử lý sự kiện xóa đáp án
                setupRemoveAnswerButtons();
            });
            
            // Xử lý sự kiện xóa đáp án
            function setupRemoveAnswerButtons() {
                const removeButtons = document.querySelectorAll('.remove-answer');
                removeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const answerOption = this.closest('.answer-option');
                        const container = document.getElementById('dap-an-container');
                        
                        if (container.querySelectorAll('.answer-option').length > 2) {
                            answerOption.remove();
                            
                            // Cập nhật lại giá trị value cho các input
                            const inputs = container.querySelectorAll('.answer-correct');
                            inputs.forEach((input, index) => {
                                input.value = index;
                            });
                            
                            // Cập nhật lại index cho các input text
                            const textInputs = container.querySelectorAll('.answer-option input[type="text"]');
                            textInputs.forEach((input, index) => {
                                input.name = `dap_an[${index}]`;
                            });
                        } else {
                            alert('Phải có ít nhất 2 đáp án!');
                        }
                    });
                });
            }
            
            // Thiết lập ban đầu cho các nút xóa đáp án
            setupRemoveAnswerButtons();
        });
    </script>
</body>
</html>
