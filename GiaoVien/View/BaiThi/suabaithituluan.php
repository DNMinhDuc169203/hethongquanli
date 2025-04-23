<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa bài thi tự luận</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
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
                <h2 class="mb-4">Sửa bài thi tự luận</h2>
                
                <form method="post" action="">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Thông tin chung</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <!-- Thêm hidden inputs để giữ giá trị -->
                                <?php if(isset($baiThi['lop_hoc_id'])): ?>
                                    <input type="hidden" name="lop_hoc_id" value="<?php echo htmlspecialchars($baiThi['lop_hoc_id']); ?>">
                                <?php endif; ?>
                                <?php if(isset($baiThi['mon_hoc_id'])): ?>
                                    <input type="hidden" name="mon_hoc_id" value="<?php echo htmlspecialchars($baiThi['mon_hoc_id']); ?>">
                                <?php endif; ?>

                                <!-- Comment các select fields -->
                                <!--
                                <div class="col-md-6">
                                    <label for="lop_hoc_id" class="form-label">Lớp học:</label>
                                    <select class="form-select" id="lop_hoc_id" name="lop_hoc_id" required>
                                        <option value="">-- Chọn lớp học --</option>
                                        <?php if(isset($danhSachLopHoc) && is_array($danhSachLopHoc)): ?>
                                            <?php foreach ($danhSachLopHoc as $lopHoc): ?>
                                                <option value="<?php echo $lopHoc['id']; ?>" <?php echo (isset($baiThi['lop_hoc_id']) && $lopHoc['id'] == $baiThi['lop_hoc_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($lopHoc['ten_lop']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="mon_hoc_id" class="form-label">Môn học:</label>
                                    <select class="form-select" id="mon_hoc_id" name="mon_hoc_id" required>
                                        <option value="">-- Chọn môn học --</option>
                                        <?php if(isset($danhSachMonHoc) && is_array($danhSachMonHoc)): ?>
                                            <?php foreach ($danhSachMonHoc as $monHoc): ?>
                                                <option value="<?php echo $monHoc['id']; ?>" <?php echo (isset($baiThi['mon_hoc_id']) && $monHoc['id'] == $baiThi['mon_hoc_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($monHoc['ten_mon']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                -->
                            </div>
                            
                            <div class="mb-3">
                                <label for="tieu_de" class="form-label">Tiêu đề bài thi:</label>
                                <input type="text" class="form-control" id="tieu_de" name="tieu_de" value="<?php echo isset($baiThi['tieu_de']) ? htmlspecialchars($baiThi['tieu_de']) : ''; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="mo_ta" class="form-label">Mô tả:</label>
                                <textarea class="form-control" id="mo_ta" name="mo_ta" rows="3"><?php echo isset($baiThi['mo_ta']) ? htmlspecialchars($baiThi['mo_ta']) : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Chế độ làm bài</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="che_do_thi" id="che_do_tren_lop" value="tren_lop" <?php echo ($baiThi['thoi_gian_lam'] !== null) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="che_do_tren_lop">Làm bài trên lớp</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="che_do_thi" id="che_do_btvn" value="btvn" <?php echo ($baiThi['thoi_gian_lam'] === null) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="che_do_btvn">Bài tập về nhà</label>
                                </div>
                            </div>
                            
                            <div id="che_do_tren_lop_options" <?php echo ($baiThi['thoi_gian_lam'] === null) ? 'style="display: none;"' : ''; ?>>
                                <div class="mb-3">
                                    <label for="thoi_gian_lam" class="form-label">Thời gian làm bài (phút):</label>
                                    <input type="number" class="form-control" id="thoi_gian_lam" name="thoi_gian_lam" min="1" value="<?php echo $baiThi['thoi_gian_lam'] ?? 60; ?>">
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Lưu ý: Khi lưu thay đổi, ngày giờ hiện tại sẽ được tự động cập nhật làm thời gian bắt đầu.
                                </div>
                            </div>
                            
                            <div id="che_do_btvn_options" <?php echo ($baiThi['thoi_gian_lam'] !== null) ? 'style="display: none;"' : ''; ?>>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="thoi_gian_bat_dau" class="form-label">Thời gian bắt đầu:</label>
                                        <input type="datetime-local" class="form-control" id="thoi_gian_bat_dau" name="thoi_gian_bat_dau" 
                                               value="<?php echo $baiThi['thoi_gian_bat_dau'] ? date('Y-m-d\TH:i', strtotime($baiThi['thoi_gian_bat_dau'])) : ''; ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="thoi_gian_ket_thuc" class="form-label">Thời gian kết thúc:</label>
                                        <input type="datetime-local" class="form-control" id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc"
                                               value="<?php echo $baiThi['thoi_gian_ket_thuc'] ? date('Y-m-d\TH:i', strtotime($baiThi['thoi_gian_ket_thuc'])) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="so_lan_lam" class="form-label">Số lần được làm:</label>
                                <input type="number" class="form-control" id="so_lan_lam" name="so_lan_lam" min="1" value="<?php echo $baiThi['so_lan_lam']; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Nội dung bài thi tự luận</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="noi_dung" class="form-label">Nhập đề bài và yêu cầu:</label>
                                <textarea class="form-control" id="noi_dung" name="noi_dung" rows="10"><?php echo $baiThi['noi_dung']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5">Lưu thay đổi</button>
                        <a href="index.php?controller=baithi" class="btn btn-outline-secondary btn-lg px-5 ms-2">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khởi tạo trình soạn thảo rich text
            $('#noi_dung').summernote({
                placeholder: 'Nhập đề bài và yêu cầu chi tiết ở đây...',
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
            
            // Chuyển đổi giữa chế độ làm bài trên lớp và bài tập về nhà
            $('input[name="che_do_thi"]').change(function() {
                if ($(this).val() === 'tren_lop') {
                    $('#che_do_tren_lop_options').show();
                    $('#che_do_btvn_options').hide();
                } else {
                    $('#che_do_tren_lop_options').hide();
                    $('#che_do_btvn_options').show();
                }
            });
            
            // Lấy danh sách môn học khi thay đổi lớp học
            $('#lop_hoc_id').change(function() {
                var lopHocId = $(this).val();
                if (lopHocId) {
                    // Gọi AJAX để lấy danh sách môn học
                    $.ajax({
                        url: 'index.php',
                        type: 'POST',
                        data: {
                            controller: 'baithi',
                            action: 'getMonHocByLopHoc',
                            lop_hoc_id: lopHocId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                var monHocSelect = $('#mon_hoc_id');
                                monHocSelect.empty();
                                monHocSelect.append('<option value="">-- Chọn môn học --</option>');
                                
                                // Thêm các tùy chọn môn học từ dữ liệu trả về
                                $.each(response.data, function(index, monHoc) {
                                    monHocSelect.append('<option value="' + monHoc.id + '">' + monHoc.ten_mon + '</option>');
                                });
                            } else {
                                alert('Không thể lấy danh sách môn học: ' + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Đã xảy ra lỗi khi lấy danh sách môn học: ' + error);
                        }
                    });
                } else {
                    // Nếu không chọn lớp học, xóa danh sách môn học
                    $('#mon_hoc_id').empty().append('<option value="">-- Chọn môn học --</option>');
                }
            });
        });
    </script>
</body>
</html> 