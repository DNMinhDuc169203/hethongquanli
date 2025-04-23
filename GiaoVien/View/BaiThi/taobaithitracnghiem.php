<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo bài thi mới</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
        .exam-form {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
                <h3>Quản lý bài thi</h3>
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
                    <a href="index.php?controller=auth&action=logout" class="btn btn-danger w-100">Đăng xuất</a>
                </div>
            </div>
            
            <!-- Main content -->
            <div class="col-md-9 content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Tạo bài thi trắc nghiệm mới</h2>
                    <!-- <a href="index.php?controller=baithi" class="btn btn-secondary">Quay lại</a> -->
                </div>
                
                <div class="exam-form">
                    <form action="index.php?controller=baithi&action=create" method="POST">
                        <div class="mb-3">
                            <label for="lop_hoc_id" class="form-label">Chọn lớp học</label>
                            <select class="form-control" id="lop_hoc_id" name="lop_hoc_id" required>
                                <option value="">-- Chọn lớp học --</option>
                                <?php foreach ($danhSachLopHoc as $lopHoc): ?>
                                    <option value="<?php echo $lopHoc['id']; ?>">
                                        <?php echo htmlspecialchars($lopHoc['ten_lop'] . ' (' . $lopHoc['ma_lop'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="mon_hoc_id" class="form-label">Chọn môn học</label>
                            <select class="form-control" id="mon_hoc_id" name="mon_hoc_id" required disabled>
                                <option value="">-- Vui lòng chọn lớp học trước --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tieu_de" class="form-label">Tiêu đề bài thi</label>
                            <input type="text" class="form-control" id="tieu_de" name="tieu_de" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="mo_ta" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="mo_ta" name="mo_ta" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Chế độ bài thi</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="che_do_thi" id="che_do_tren_lop" value="tren_lop" checked onchange="toggleCheDoThi()">
                                <label class="form-check-label" for="che_do_tren_lop">
                                    Làm bài trên lớp
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="che_do_thi" id="che_do_btvn" value="btvn" onchange="toggleCheDoThi()">
                                <label class="form-check-label" for="che_do_btvn">
                                    Bài tập về nhà
                                </label>
                            </div>
                        </div>
                        
                        <!-- Phần thời gian làm bài trên lớp -->
                        <div id="thoi_gian_tren_lop" class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="thoi_gian_lam" class="form-label">Thời gian làm bài (phút)</label>
                                    <input type="number" class="form-control" id="thoi_gian_lam" name="thoi_gian_lam" value="60" min="1">
                                </div>
                                <div class="col-md-6">
                                    <label for="thoi_gian_bat_dau" class="form-label">Thời gian bắt đầu</label>
                                    <input type="datetime-local" class="form-control" id="thoi_gian_bat_dau" name="thoi_gian_bat_dau" 
                                           value="<?php echo date('Y-m-d\TH:i', strtotime('now')); ?>"
                                           min="<?php echo date('Y-m-d\TH:i', strtotime('now')); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Phần thời gian làm bài tập về nhà -->
                        <div id="thoi_gian_btvn" class="mb-3" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="thoi_gian_bat_dau_btvn" class="form-label">Thời gian bắt đầu</label>
                                    <input type="datetime-local" class="form-control" id="thoi_gian_bat_dau_btvn" name="thoi_gian_bat_dau" value="<?php echo date('Y-m-d\TH:i'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="thoi_gian_ket_thuc" class="form-label">Thời gian kết thúc</label>
                                    <input type="datetime-local" class="form-control" id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="tron_cau_hoi" name="tron_cau_hoi" value="1">
                                    <label class="form-check-label" for="tron_cau_hoi">
                                        Trộn câu hỏi
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="tron_dap_an" name="tron_dap_an" value="1">
                                    <label class="form-check-label" for="tron_dap_an">
                                        Trộn đáp án
                                    </label>
                                </div>
                            </div>
                            
                            <!-- <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hien_thi_dap_an" name="hien_thi_dap_an" value="1">
                                    <label class="form-check-label" for="hien_thi_dap_an">
                                        Hiển thị đáp án
                                    </label>
                                </div>
                            </div> -->
                            
                            <div class="col-md-3">
                                <label for="so_lan_lam" class="form-label">Số lần làm tối đa</label>
                                <input type="number" class="form-control" id="so_lan_lam" name="so_lan_lam" min="1" value="1">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold">Chủ đề và câu hỏi</label>
                                <button type="button" class="btn btn-sm btn-success" id="btnThemChuDe">+ Thêm chủ đề</button>
                            </div>
                            
                            <div id="danhSachChuDe">
                                <!-- Template chủ đề sẽ được thêm vào đây -->
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Tạo bài thi</button>
                            <a href="index.php?controller=baithi" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Template cho chủ đề -->
    <template id="templateChuDe">
        <div class="chu-de-item border rounded p-3 mb-3">
            <div class="row mb-3">
                <div class="col-md-10">
                    <div class="d-flex align-items-center">
                        <label class="form-label me-2">Chủ đề:</label>
                        <select class="form-select chu-de-select" name="chu_de[__INDEX__][id]" required>
                            <option value="">-- Vui lòng chọn môn học trước --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-sm btn-danger btn-xoa-chu-de">Xóa</button>
                </div>
            </div>
            
            <div class="cau-hoi-container">
                <div class="border p-3 rounded">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-bold">Chọn câu hỏi:</div>
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control form-control-sm me-2 auto-select-number" 
                                   placeholder="Số câu hỏi" min="1" style="width: 100px;">
                            <button type="button" class="btn btn-sm btn-primary btn-auto-select">Chọn tự động</button>
                        </div>
                    </div>
                    <div class="cau-hoi-list">
                        <p class="text-muted">Vui lòng chọn chủ đề trước để xem danh sách câu hỏi</p>
                    </div>
                </div>
            </div>
        </div>
    </template>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Đặt giá trị mặc định cho trường thời gian bắt đầu với múi giờ Việt Nam
            const now = new Date();
            const offset = 7; // Múi giờ UTC+7 (Việt Nam)
            // Chuyển đổi giờ hiện tại sang giờ Việt Nam
            now.setHours(now.getHours() + offset - now.getTimezoneOffset() / 60);
            
            const formattedDate = now.toISOString().slice(0, 16);
            document.getElementById('thoi_gian_bat_dau').value = formattedDate;
            if (document.getElementById('thoi_gian_bat_dau_btvn')) {
                document.getElementById('thoi_gian_bat_dau_btvn').value = formattedDate;
            }

            // Kiểm tra thời gian kết thúc phải sau thời gian bắt đầu
            function validateDateTime() {
                const startTime = new Date($('#thoi_gian_bat_dau_btvn').val());
                const endTime = new Date($('#thoi_gian_ket_thuc').val());
                
                if (!startTime || !endTime) return true;
                
                if (endTime <= startTime) {
                    alert('Thời gian kết thúc phải sau thời gian bắt đầu!');
                    $('#thoi_gian_ket_thuc').val('');
                    return false;
                }
                return true;
            }

            // Thêm sự kiện kiểm tra khi thay đổi thời gian
            $('#thoi_gian_ket_thuc').on('change', validateDateTime);
            $('#thoi_gian_bat_dau_btvn').on('change', function() {
                if($('#thoi_gian_ket_thuc').val()) {
                    validateDateTime();
                }
            });

            // Kiểm tra form trước khi submit
            $('form').on('submit', function(e) {
                if($('#che_do_btvn').is(':checked')) {
                    if(!$('#thoi_gian_bat_dau_btvn').val() || !$('#thoi_gian_ket_thuc').val()) {
                        alert('Vui lòng nhập đầy đủ thời gian bắt đầu và kết thúc!');
                        e.preventDefault();
                        return false;
                    }
                    if(!validateDateTime()) {
                        e.preventDefault();
                        return false;
                    }
                }
            });

            let chuDeIndex = 0;
            const danhSachChuDe = document.getElementById('danhSachChuDe');
            const templateChuDe = document.getElementById('templateChuDe');
            
            // Mảng lưu trữ ID các câu hỏi đã được chọn
            let daCoCauHoi = [];
            let daSoLuongCauHoi = 0; // Biến để theo dõi số lượng câu hỏi đã chọn
            
            // Kiểm tra form trước khi submit
            document.querySelector('form').addEventListener('submit', function(e) {
                if (daSoLuongCauHoi === 0) {
                    e.preventDefault();
                    alert('Vui lòng chọn ít nhất một câu hỏi cho bài thi!');
                    return false;
                }
                return true;
            });
            
            // Sự kiện khi chọn lớp học
            document.getElementById('lop_hoc_id').addEventListener('change', function() {
                const lopHocId = this.value;
                const monHocSelect = document.getElementById('mon_hoc_id');
                
                // Reset môn học
                monHocSelect.innerHTML = '<option value="">-- Đang tải danh sách môn học --</option>';
                monHocSelect.disabled = true;
                
                if (lopHocId) {
                    // Gọi AJAX để lấy danh sách môn học của lớp
                    fetch(`index.php?controller=baithi&action=create&lop_hoc_id=${lopHocId}`)
                        .then(response => response.json())
                        .then(data => {
                            monHocSelect.innerHTML = '<option value="">-- Chọn môn học --</option>';
                            
                            if (data.length === 0) {
                                monHocSelect.innerHTML = '<option value="">Không có môn học nào cho lớp này</option>';
                            } else {
                                data.forEach(monHoc => {
                                    const option = document.createElement('option');
                                    option.value = monHoc.id;
                                    option.textContent = monHoc.ten_mon;
                                    monHocSelect.appendChild(option);
                                });
                            }
                            
                            monHocSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Lỗi khi tải danh sách môn học:', error);
                            monHocSelect.innerHTML = '<option value="">-- Lỗi khi tải dữ liệu --</option>';
                            monHocSelect.disabled = false;
                        });
                } else {
                    monHocSelect.innerHTML = '<option value="">-- Vui lòng chọn lớp học trước --</option>';
                    monHocSelect.disabled = true;
                }
                
                // Reset các chủ đề đã có
                danhSachChuDe.innerHTML = '';
                daCoCauHoi = [];
                daSoLuongCauHoi = 0;
            });
            
            // Sự kiện khi chọn môn học
            document.getElementById('mon_hoc_id').addEventListener('change', function() {
                // Reset các chủ đề đã có
                danhSachChuDe.innerHTML = '';
                daCoCauHoi = [];
                
                // Tạo chủ đề mới nếu đã chọn môn học
                if (this.value) {
                    themChuDe();
                }
            });
            
            // Thêm chủ đề
            document.getElementById('btnThemChuDe').addEventListener('click', function() {
                const monHocId = document.getElementById('mon_hoc_id').value;
                if (!monHocId) {
                    alert('Vui lòng chọn môn học trước khi thêm chủ đề');
                    return;
                }
                themChuDe();
            });
            
            // Hàm thêm chủ đề mới
            function themChuDe() {
                const chuDeItem = document.importNode(templateChuDe.content, true);
                const monHocId = document.getElementById('mon_hoc_id').value;
                
                // Cập nhật index
                chuDeItem.querySelectorAll('[name*="__INDEX__"]').forEach(el => {
                    el.name = el.name.replace('__INDEX__', chuDeIndex);
                });
                
                chuDeItem.querySelectorAll('[for*="__INDEX__"]').forEach(el => {
                    el.setAttribute('for', el.getAttribute('for').replace('__INDEX__', chuDeIndex));
                });
                
                chuDeItem.querySelectorAll('[id*="__INDEX__"]').forEach(el => {
                    el.id = el.id.replace('__INDEX__', chuDeIndex);
                });
                
                // Xử lý sự kiện xóa chủ đề
                chuDeItem.querySelector('.btn-xoa-chu-de').addEventListener('click', function() {
                    const chuDeItem = this.closest('.chu-de-item');
                    // Trước khi xóa, bỏ chọn tất cả các câu hỏi để mở khóa chúng
                    chuDeItem.querySelectorAll('.cau-hoi-checkbox:checked').forEach(cb => {
                        cb.checked = false;
                        quanLyCauHoi(cb);
                    });
                    chuDeItem.remove();
                });
                
                // Xử lý sự kiện chọn tự động
                chuDeItem.querySelector('.btn-auto-select').addEventListener('click', function() {
                    const chuDeContainer = this.closest('.chu-de-item');
                    const numberInput = chuDeContainer.querySelector('.auto-select-number');
                    const numToSelect = parseInt(numberInput.value);
                    
                    if (isNaN(numToSelect) || numToSelect <= 0) {
                        alert('Vui lòng nhập số lượng câu hỏi hợp lệ.');
                        return;
                    }
                    
                    // Lấy tất cả câu hỏi khả dụng (chưa bị disabled)
                    const availableQuestions = Array.from(
                        chuDeContainer.querySelectorAll('.cau-hoi-checkbox:not(:disabled):not(:checked)')
                    );
                    
                    if (availableQuestions.length === 0) {
                        alert('Không còn câu hỏi khả dụng để chọn.');
                        return;
                    }
                    
                    if (numToSelect > availableQuestions.length) {
                        alert(`Chỉ có ${availableQuestions.length} câu hỏi khả dụng. Sẽ chọn tất cả câu hỏi khả dụng.`);
                    }
                    
                    // Lấy số lượng câu hỏi ngẫu nhiên theo số đã nhập
                    const questionsToSelect = shuffleArray(availableQuestions)
                        .slice(0, Math.min(numToSelect, availableQuestions.length));
                    
                    // Chọn các câu hỏi
                    questionsToSelect.forEach(checkbox => {
                        checkbox.checked = true;
                        quanLyCauHoi(checkbox);
                    });
                });
                
                const chuDeSelect = chuDeItem.querySelector('.chu-de-select');
                
                // Tải danh sách chủ đề cho môn học
                chuDeSelect.innerHTML = '<option value="">-- Đang tải chủ đề --</option>';
                
                fetch(`index.php?controller=baithi&action=create&mon_hoc_id=${monHocId}`)
                    .then(response => response.json())
                    .then(data => {
                        chuDeSelect.innerHTML = '<option value="">-- Chọn chủ đề --</option>';
                        
                        if (data.length === 0) {
                            chuDeSelect.innerHTML = '<option value="">Không có chủ đề nào cho môn học này</option>';
                        } else {
                            data.forEach(chuDe => {
                                const option = document.createElement('option');
                                option.value = chuDe.id;
                                option.textContent = chuDe.ten_chu_de;
                                chuDeSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi tải danh sách chủ đề:', error);
                        chuDeSelect.innerHTML = '<option value="">-- Lỗi khi tải dữ liệu --</option>';
                    });
                
                // Xử lý sự kiện khi chọn chủ đề để tải câu hỏi
                chuDeSelect.addEventListener('change', function() {
                    const chuDeId = this.value;
                    const cauHoiList = this.closest('.chu-de-item').querySelector('.cau-hoi-list');
                    
                    if (!chuDeId) {
                        cauHoiList.innerHTML = '<p class="text-muted">Vui lòng chọn chủ đề để xem danh sách câu hỏi</p>';
                        return;
                    }
                    
                    cauHoiList.innerHTML = '<p class="text-muted">Đang tải danh sách câu hỏi...</p>';
                    
                    // Lưu lại index hiện tại của chủ đề này trong DOM để tránh lỗi
                    const currentIndex = chuDeIndex - 1;
                    
                    fetch(`index.php?controller=baithi&action=create&chu_de_id=${chuDeId}`)
                        .then(response => response.json())
                        .then(data => {
                            cauHoiList.innerHTML = '';
                            
                            if (data.length === 0) {
                                cauHoiList.innerHTML = '<p class="text-muted">Không có câu hỏi nào cho chủ đề này</p>';
                                return;
                            }
                            
                            data.forEach(cauHoi => {
                                const cauHoiId = cauHoi.id;
                                const disabled = daCoCauHoi.includes(cauHoiId.toString()) ? 'disabled' : '';
                                const textMuted = disabled ? 'text-muted' : '';
                                
                                cauHoiList.innerHTML += `
                                    <div class="form-check mb-2 cau-hoi-item ${textMuted}">
                                        <input class="form-check-input cau-hoi-checkbox" type="checkbox" 
                                               name="chu_de[${currentIndex}][cau_hoi_ids][]" value="${cauHoiId}" 
                                               id="chu_de_${currentIndex}_cauhoi_${cauHoiId}" ${disabled}
                                               data-chu-de-id="${chuDeId}">
                                        <label class="form-check-label" for="chu_de_${currentIndex}_cauhoi_${cauHoiId}">
                                            ${cauHoi.noi_dung}
                                        </label>
                                        <input type="hidden" name="chu_de[${currentIndex}][cau_hoi_chu_de][${cauHoiId}]" value="${chuDeId}">
                                    </div>
                                `;
                            });
                            
                            // Thêm sự kiện cho các checkbox câu hỏi
                            cauHoiList.querySelectorAll('.cau-hoi-checkbox').forEach(checkbox => {
                                checkbox.addEventListener('change', function() {
                                    quanLyCauHoi(this);
                                });
                            });
                        })
                        .catch(error => {
                            console.error('Lỗi khi tải danh sách câu hỏi:', error);
                            cauHoiList.innerHTML = '<p class="text-danger">Lỗi khi tải danh sách câu hỏi</p>';
                        });
                });
                
                danhSachChuDe.appendChild(chuDeItem);
                chuDeIndex++;
            }
            
            // Hàm quản lý trạng thái câu hỏi
            function quanLyCauHoi(checkbox) {
                const cauHoiId = checkbox.value;
                
                if (checkbox.checked) {
                    // Thêm vào danh sách câu hỏi đã chọn
                    if (!daCoCauHoi.includes(cauHoiId)) {
                        daCoCauHoi.push(cauHoiId);
                        daSoLuongCauHoi++;
                    }
                    
                    // Vô hiệu hóa cùng câu hỏi ở các chủ đề khác
                    document.querySelectorAll(`.cau-hoi-checkbox[value="${cauHoiId}"]`).forEach(cb => {
                        if (cb !== checkbox) {
                            cb.disabled = true;
                            cb.parentElement.classList.add('text-muted');
                        }
                    });
                } else {
                    // Xóa khỏi danh sách câu hỏi đã chọn
                    daCoCauHoi = daCoCauHoi.filter(id => id !== cauHoiId);
                    daSoLuongCauHoi--;
                    
                    // Mở lại khả năng chọn ở các chủ đề khác
                    document.querySelectorAll(`.cau-hoi-checkbox[value="${cauHoiId}"]`).forEach(cb => {
                        cb.disabled = false;
                        cb.parentElement.classList.remove('text-muted');
                    });
                }
            }
            
            // Thêm ít nhất một chủ đề mặc định
            themChuDe();
            
            // Hàm để trộn mảng (thuật toán Fisher-Yates)
            function shuffleArray(array) {
                for (let i = array.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [array[i], array[j]] = [array[j], array[i]];
                }
                return array;
            }
        });

        function toggleCheDoThi() {
            const cheDoBTVN = document.getElementById('che_do_btvn').checked;
            const thoiGianTrenLop = document.getElementById('thoi_gian_tren_lop');
            const thoiGianBTVN = document.getElementById('thoi_gian_btvn');
            
            if (cheDoBTVN) {
                thoiGianTrenLop.style.display = 'none';
                thoiGianBTVN.style.display = 'block';
                document.getElementById('thoi_gian_lam').value = '';
            } else {
                thoiGianTrenLop.style.display = 'block';
                thoiGianBTVN.style.display = 'none';
                document.getElementById('thoi_gian_bat_dau_btvn').value = '';
                document.getElementById('thoi_gian_ket_thuc').value = '';
            }
        }

        // Gọi hàm khi trang được tải
        document.addEventListener('DOMContentLoaded', function() {
            toggleCheDoThi();
        });
    </script>
</body>
</html>