<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm bài thi tự luận mới</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css">
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
        .form-section {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .note-editor {
            margin-bottom: 20px;
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
                    <h2>Thêm bài thi tự luận mới</h2>
                    <a href="index.php?controller=baithi" class="btn btn-secondary">Quay lại</a>
                </div>
                
                <form action="index.php?controller=baithituluan&action=add" method="post" enctype="multipart/form-data">
                    <div class="form-section">
                        <h4 class="mb-3">Thông tin cơ bản</h4>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="lop_hoc_id" class="form-label">Lớp học <span class="text-danger">*</span></label>
                                <select name="lop_hoc_id" id="lop_hoc_id" class="form-select" required>
                                    <option value="">Chọn lớp học</option>
                                    <?php foreach ($danhSachLopHoc as $lopHoc): ?>
                                    <option value="<?php echo $lopHoc['id']; ?>">
                                        <?php echo htmlspecialchars($lopHoc['ten_lop'] . ' (' . $lopHoc['ma_lop'] . ')'); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="mon_hoc_id" class="form-label">Môn học <span class="text-danger">*</span></label>
                                <select name="mon_hoc_id" id="mon_hoc_id" class="form-select" required disabled>
                                    <option value="">Chọn môn học</option>
                                </select>
                                <div class="form-text">Vui lòng chọn lớp học trước</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tieu_de" class="form-label">Tiêu đề bài thi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="tieu_de" name="tieu_de" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="mo_ta" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="mo_ta" name="mo_ta" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="noi_dung" class="form-label">Nội dung bài thi <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="noi_dung" name="noi_dung"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="dinh_kem" class="form-label">Đính kèm (tùy chọn)</label>
                            <input type="file" class="form-control" id="dinh_kem" name="dinh_kem">
                            <div class="form-text">Hỗ trợ: .pdf, .docx, .xlsx, .jpg, .png. Kích thước tối đa: 10MB</div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h4 class="mb-3">Thiết lập thời gian</h4>
                        
                        <div class="mb-3">
                            <label class="form-label d-block">Loại bài thi <span class="text-danger">*</span></label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="loai_bai_thi" id="loai_tren_lop" value="tren_lop" checked>
                                <label class="form-check-label" for="loai_tren_lop">Làm trên lớp</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="loai_bai_thi" id="loai_ve_nha" value="ve_nha">
                                <label class="form-check-label" for="loai_ve_nha">Bài tập về nhà</label>
                            </div>
                        </div>
                        
                        <div id="tren-lop-options">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="thoi_gian_lam" class="form-label">Thời gian làm bài (phút) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="thoi_gian_lam" name="thoi_gian_lam" min="1" value="60">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="thoi_gian_bat_dau" class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="thoi_gian_bat_dau" name="thoi_gian_bat_dau" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="thoi_gian_ket_thuc" class="form-label">Thời gian kết thúc</label>
                                <input type="datetime-local" class="form-control" id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc">
                                <div class="form-text">Để trống nếu không có thời hạn cụ thể</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary btn-lg">Tạo bài thi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('#noi_dung').summernote({
                placeholder: 'Nhập nội dung bài thi tại đây...',
                tabsize: 2,
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
            
            // Handle change in lớp học selection
            $('#lop_hoc_id').change(function() {
                var lopHocId = $(this).val();
                if (lopHocId) {
                    // Fetch list of subjects for the selected class
                    $.ajax({
                        url: 'index.php?controller=baithituluan&action=getMonHocByLopHoc',
                        method: 'POST',
                        data: { lop_hoc_id: lopHocId },
                        dataType: 'json',
                        success: function(response) {
                            var options = '<option value="">Chọn môn học</option>';
                            $.each(response, function(index, monHoc) {
                                options += '<option value="' + monHoc.id + '">' + monHoc.ten_mon + '</option>';
                            });
                            $('#mon_hoc_id').html(options).prop('disabled', false);
                        },
                        error: function() {
                            alert('Đã xảy ra lỗi khi tải danh sách môn học');
                        }
                    });
                } else {
                    $('#mon_hoc_id').html('<option value="">Chọn môn học</option>').prop('disabled', true);
                }
            });
            
            // Handle change in exam type
            $('input[name="loai_bai_thi"]').change(function() {
                if ($(this).val() === 'tren_lop') {
                    $('#tren-lop-options').removeClass('d-none');
                } else {
                    $('#tren-lop-options').addClass('d-none');
                }
            });
            
            // Set default date/time values
            var now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            
            // Set default start time to now
            document.getElementById('thoi_gian_bat_dau').value = now.toISOString().slice(0, 16);
            
            // Set default end time to now + 7 days
            var endDate = new Date();
            endDate.setDate(endDate.getDate() + 7);
            endDate.setMinutes(endDate.getMinutes() - endDate.getTimezoneOffset());
            document.getElementById('thoi_gian_ket_thuc').value = endDate.toISOString().slice(0, 16);
        });
    </script>
</body>
</html> 