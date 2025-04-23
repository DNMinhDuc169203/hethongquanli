<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo câu hỏi trắc nghiệm</title>
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
        .question-form {
            background-color: #e0f7ea;
            padding: 20px;
            border-radius: 5px;
        }
        .answer-option {
            margin-bottom: 10px;
        }
     
        .question-block {
            background-color: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
        }
        .question-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
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
                <?php if (isset($_GET['chu_de_id']) || isset($_POST['chu_de_id'])): ?>
                <?php 
                    $chuDeId = $_GET['chu_de_id'] ?? $_POST['chu_de_id'] ?? null;
                    $monHocId = $_GET['mon_hoc_id'] ?? $_POST['mon_hoc_id'] ?? null;
                ?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?controller=cauhoi">Danh sách môn học</a></li>
                        <?php if ($monHocId): ?>
                        <li class="breadcrumb-item">
                            <a href="index.php?controller=cauhoi&action=viewBySubject&id=<?= $monHocId ?>">
                                Danh sách chủ đề
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if ($chuDeId): ?>
                        <li class="breadcrumb-item">
                            <a href="index.php?controller=cauhoi&action=viewByTopic&id=<?= $chuDeId ?>">
                                Danh sách câu hỏi
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="breadcrumb-item active" aria-current="page">Tạo câu hỏi mới</li>
                    </ol>
                </nav>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Tạo câu hỏi trắc nghiệm</h2>
                    <?php if (isset($_GET['chu_de_id']) || isset($_POST['chu_de_id'])): ?>
                    <a href="index.php?controller=cauhoi&action=viewByTopic&id=<?= $_GET['chu_de_id'] ?? $_POST['chu_de_id'] ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <?php else: ?>
                    <a href="index.php?controller=cauhoi" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <?php endif; ?>
                </div>
                
                <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <strong>Lỗi:</strong> <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body question-form">
                        <form action="index.php?controller=cauhoi&action=create<?= isset($_GET['chu_de_id']) ? '&chu_de_id=' . $_GET['chu_de_id'] : '' ?><?= isset($_GET['mon_hoc_id']) ? '&mon_hoc_id=' . $_GET['mon_hoc_id'] : '' ?>" method="POST">
                            <?php if (isset($_GET['chu_de_id']) || isset($_POST['chu_de_id'])): ?>
                                <input type="hidden" name="chu_de_id" value="<?= $_GET['chu_de_id'] ?? $_POST['chu_de_id'] ?>">
                                <?php if (isset($_GET['mon_hoc_id']) || isset($_POST['mon_hoc_id'])): ?>
                                <input type="hidden" name="mon_hoc_id" value="<?= $_GET['mon_hoc_id'] ?? $_POST['mon_hoc_id'] ?>">
                                <?php endif; ?>
                            <?php else: ?>
                            <div class="mb-3">
                                <label for="chu_de_id" class="form-label">Chọn chủ đề *</label>
                                <select class="form-select" id="chu_de_id" name="chu_de_id" required>
                                    <option value="">-- Chọn chủ đề --</option>
                                    <?php foreach ($danhSachChuDe as $chuDe): ?>
                                        <option value="<?= $chuDe['id'] ?>"><?= htmlspecialchars($chuDe['ten_chu_de']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="tieu_de" class="form-label">Tiêu đề chung</label>
                                <input type="text" class="form-control" id="tieu_de" name="tieu_de" required>
                            </div>
                            
                            <div id="questions-container">
                                <div class="question-block" data-question-index="0">
                                    <div class="question-header">
                                        <h4>Câu hỏi #1</h4>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-question" style="display: none;">Xóa câu hỏi</button>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Nội dung câu hỏi</label>
                                        <input type="text" class="form-control" name="cau_hoi[0]" placeholder="..." required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="multiple_correct_0" name="multiple_correct[0]" value="1">
                                            <label class="form-check-label" for="multiple_correct_0">
                                                Cho phép nhiều đáp án đúng
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="dap-an-container" data-question-index="0">
                                        <div class="answer-option">
                                            <div class="input-group">
                                                <div class="input-group-text answer-checkbox-container" data-question-index="0">
                                                    <input class="form-check-input answer-correct" type="radio" name="dap_an_dung[0][]" value="0" checked>
                                                </div>
                                                <input type="text" class="form-control" name="dap_an[0][]" placeholder="..." required>
                                                <button type="button" class="btn btn-outline-danger remove-answer">X</button>
                                            </div>
                                        </div>
                                        <div class="answer-option">
                                            <div class="input-group">
                                                <div class="input-group-text answer-checkbox-container" data-question-index="0">
                                                    <input class="form-check-input answer-correct" type="radio" name="dap_an_dung[0][]" value="1">
                                                </div>
                                                <input type="text" class="form-control" name="dap_an[0][]" placeholder="..." required>
                                                <button type="button" class="btn btn-outline-danger remove-answer">X</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-info add-answer" data-question-index="0">Thêm đáp án</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="button" id="add-question" class="btn btn-success">Thêm câu hỏi mới</button>
                            </div>
                            
                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary">Tạo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tự động ẩn thông báo sau 4 giây
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    // Tạo đối tượng bootstrap alert
                    const bsAlert = new bootstrap.Alert(alert);
                    
                    // Sử dụng hiệu ứng fade out trước khi đóng
                    alert.classList.remove('show');
                    
                    // Đóng thông báo sau khi hiệu ứng fade out hoàn tất (300ms)
                    setTimeout(function() {
                        bsAlert.close();
                    }, 300);
                }, 4000); // 4 giây
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý chuyển đổi giữa radio và checkbox
            function setupMultipleCorrectCheckbox(questionIndex) {
                const multipleCorrectCheckbox = document.getElementById(`multiple_correct_${questionIndex}`);
                const answerCheckboxContainer = document.querySelectorAll(`.answer-checkbox-container[data-question-index="${questionIndex}"]`);
                
                multipleCorrectCheckbox.addEventListener('change', function() {
                    const inputType = this.checked ? 'checkbox' : 'radio';
                    const answerInputs = document.querySelectorAll(`.answer-checkbox-container[data-question-index="${questionIndex}"] .answer-correct`);
                    
                    // Thay đổi loại input
                    answerInputs.forEach((input, index) => {
                        const newInput = document.createElement('input');
                        newInput.className = 'form-check-input answer-correct';
                        newInput.type = inputType;
                        newInput.name = `dap_an_dung[${questionIndex}][]`;
                        newInput.value = index;
                        
                        // Giữ lại trạng thái checked nếu có
                        if (input.checked) {
                            newInput.checked = true;
                        }
                        
                        // Thay thế input cũ bằng input mới
                        input.parentNode.replaceChild(newInput, input);
                    });
                });
            }
            
            // Thiết lập ban đầu cho câu hỏi đầu tiên
            setupMultipleCorrectCheckbox(0);
            
            // Thêm đáp án mới cho một câu hỏi cụ thể
            function setupAddAnswerButton(questionIndex) {
                const addAnswerBtn = document.querySelector(`.add-answer[data-question-index="${questionIndex}"]`);
                
                addAnswerBtn.addEventListener('click', function() {
                    const container = document.querySelector(`.dap-an-container[data-question-index="${questionIndex}"]`);
                    const answerCount = container.children.length;
                    
                    // Kiểm tra số lượng đáp án
                    if (answerCount >= 4) {
                        alert('Chỉ được phép tạo tối đa 4 đáp án!');
                        return;
                    }
                    
                    const multipleCorrect = document.getElementById(`multiple_correct_${questionIndex}`).checked;
                    const inputType = multipleCorrect ? 'checkbox' : 'radio';
                    
                    const newAnswer = document.createElement('div');
                    newAnswer.className = 'answer-option';
                    newAnswer.innerHTML = `
                        <div class="input-group mb-2">
                            <div class="input-group-text answer-checkbox-container" data-question-index="${questionIndex}">
                                <input class="form-check-input answer-correct" type="${inputType}" name="dap_an_dung[${questionIndex}][]" value="${answerCount}">
                            </div>
                            <input type="text" class="form-control" name="dap_an[${questionIndex}][]" placeholder="..." required>
                            <button type="button" class="btn btn-outline-danger remove-answer">X</button>
                        </div>
                    `;
                    
                    container.appendChild(newAnswer);
                    
                    // Xử lý sự kiện xóa đáp án
                    setupRemoveAnswerButtons();
                });
            }
            
            // Thiết lập ban đầu cho nút thêm đáp án của câu hỏi đầu tiên
            setupAddAnswerButton(0);
            
            // Xử lý sự kiện xóa đáp án
            function setupRemoveAnswerButtons() {
                const removeButtons = document.querySelectorAll('.remove-answer');
                removeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const answerOption = this.closest('.answer-option');
                        const container = answerOption.parentElement;
                        
                        if (container.children.length > 2) {
                            answerOption.remove();
                            
                            // Cập nhật lại giá trị value cho các input radio/checkbox
                            const questionIndex = container.getAttribute('data-question-index');
                            const inputs = container.querySelectorAll('.answer-correct');
                            inputs.forEach((input, index) => {
                                input.value = index;
                            });
                        } else {
                            alert('Phải có ít nhất 2 đáp án!');
                        }
                    });
                });
            }
            
            // Thiết lập ban đầu cho các nút xóa đáp án
            setupRemoveAnswerButtons();
            
            // Thêm câu hỏi mới
            let questionCounter = 1;
            document.getElementById('add-question').addEventListener('click', function() {
                const container = document.getElementById('questions-container');
                const questionIndex = questionCounter;
                questionCounter++;
                
                const newQuestion = document.createElement('div');
                newQuestion.className = 'question-block';
                newQuestion.setAttribute('data-question-index', questionIndex);
                newQuestion.innerHTML = `
                    <div class="question-header">
                        <h4>Câu hỏi #${questionCounter}</h4>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-question">Xóa câu hỏi</button>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nội dung câu hỏi</label>
                        <input type="text" class="form-control" name="cau_hoi[${questionIndex}]" placeholder="..." required>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="multiple_correct_${questionIndex}" name="multiple_correct[${questionIndex}]" value="1">
                            <label class="form-check-label" for="multiple_correct_${questionIndex}">
                                Cho phép nhiều đáp án đúng
                            </label>
                        </div>
                    </div>
                    
                    <div class="dap-an-container" data-question-index="${questionIndex}">
                        <div class="answer-option">
                            <div class="input-group">
                                <div class="input-group-text answer-checkbox-container" data-question-index="${questionIndex}">
                                    <input class="form-check-input answer-correct" type="radio" name="dap_an_dung[${questionIndex}][]" value="0" checked>
                                </div>
                                <input type="text" class="form-control" name="dap_an[${questionIndex}][]" placeholder="..." required>
                                <button type="button" class="btn btn-outline-danger remove-answer">X</button>
                            </div>
                        </div>
                        <div class="answer-option">
                            <div class="input-group">
                                <div class="input-group-text answer-checkbox-container" data-question-index="${questionIndex}">
                                    <input class="form-check-input answer-correct" type="radio" name="dap_an_dung[${questionIndex}][]" value="1">
                                </div>
                                <input type="text" class="form-control" name="dap_an[${questionIndex}][]" placeholder="..." required>
                                <button type="button" class="btn btn-outline-danger remove-answer">X</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="button" class="btn btn-info add-answer" data-question-index="${questionIndex}">Thêm đáp án</button>
                    </div>
                `;
                
                container.appendChild(newQuestion);
                
                // Thiết lập các sự kiện cho câu hỏi mới
                setupMultipleCorrectCheckbox(questionIndex);
                setupAddAnswerButton(questionIndex);
                setupRemoveAnswerButtons();
                setupRemoveQuestionButtons();
                
                // Hiển thị nút xóa câu hỏi cho câu hỏi đầu tiên nếu có nhiều hơn 1 câu hỏi
                if (questionCounter > 1) {
                    const firstQuestionRemoveBtn = document.querySelector('.question-block[data-question-index="0"] .remove-question');
                    if (firstQuestionRemoveBtn) {
                        firstQuestionRemoveBtn.style.display = 'block';
                    }
                }
            });
            
            // Xử lý sự kiện xóa câu hỏi
            function setupRemoveQuestionButtons() {
                const removeButtons = document.querySelectorAll('.remove-question');
                removeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const questionBlock = this.closest('.question-block');
                        const container = document.getElementById('questions-container');
                        
                        if (container.children.length > 1) {
                            questionBlock.remove();
                            
                            // Cập nhật lại số thứ tự hiển thị của các câu hỏi
                            const questionBlocks = container.querySelectorAll('.question-block');
                            questionBlocks.forEach((block, index) => {
                                block.querySelector('h4').textContent = `Câu hỏi #${index + 1}`;
                            });
                            
                            // Ẩn nút xóa câu hỏi nếu chỉ còn 1 câu hỏi
                            if (container.children.length === 1) {
                                const lastRemoveBtn = container.querySelector('.remove-question');
                                if (lastRemoveBtn) {
                                    lastRemoveBtn.style.display = 'none';
                                }
                            }
                        } else {
                            alert('Phải có ít nhất 1 câu hỏi!');
                        }
                    });
                });
            }
            
            // Thiết lập ban đầu cho các nút xóa câu hỏi
            setupRemoveQuestionButtons();
        });
    </script>
</body>
</html> 