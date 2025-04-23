<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm môn học mới</title>
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
        .subject-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
            height: 100%;
        }
        .subject-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .subject-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #0d6efd;
        }
     
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .subject-info {
            flex-grow: 1;
        }
        .subject-actions {
            margin-top: 15px;
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
                       </i> Đăng xuất
                    </a>
                </div>
          
            </div>
            
            <!-- Main content -->
            <div class="col-md-9 content">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?controller=cauhoi">Danh sách môn học</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm môn học mới</li>
                    </ol>
                </nav>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Thêm môn học mới</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error) && !empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>
                        
                        <!-- <?php if (!empty($danhSachMonHoc)): ?>
                        <div class="alert alert-info mb-3">
                            <p><strong>Môn học đã tạo của bạn:</strong></p>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($danhSachMonHoc as $monHoc): ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($monHoc['ma_mon']) ?> (<?= htmlspecialchars($monHoc['ten_mon']) ?>)</span>
                                <?php endforeach; ?>
                            </div>
                            <p class="mt-2 mb-0"><small>Bạn không thể tạo hai môn học với cùng một mã.</small></p>
                        </div>
                        <?php endif; ?> -->
                        
                        <form action="index.php?controller=cauhoi&action=createSubject" method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="ma_mon" class="form-label">Mã môn học *</label>
                                    <input type="text" class="form-control" id="ma_mon" name="ma_mon" required autocomplete="off">
                                    <!-- <div class="form-text">Mã môn học không được trùng với mã đã tồn tại.</div> -->
                                </div>
                                <div class="col-md-6">
                                    <label for="ten_mon" class="form-label">Tên môn học *</label>
                                    <input type="text" class="form-control" id="ten_mon" name="ten_mon" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="mo_ta" class="form-label">Mô tả</label>
                                <textarea class="form-control" id="mo_ta" name="mo_ta" rows="3"></textarea>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="hoc_ky" class="form-label">Học kỳ</label>
                                    <input type="text" class="form-control" id="hoc_ky" name="hoc_ky">
                                </div>
                                <div class="col-md-4">
                                    <label for="nam_hoc" class="form-label">Năm học</label>
                                    <input type="text" class="form-control" id="nam_hoc" name="nam_hoc" pattern="[0-9-]+" 
                                           oninput="this.value = this.value.replace(/[^0-9-]/g, '')" 
                                           title="Chỉ được nhập số và dấu gạch ngang">
                                    <!-- <div class="form-text">Chỉ được nhập số (ví dụ: 2023-2024)</div> -->
                                </div>
                                <div class="col-md-4">
                                    <label for="so_tin_chi" class="form-label">Số tín chỉ</label>
                                    <input type="number" class="form-control" id="so_tin_chi" name="so_tin_chi" min="1" value="3">
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Lưu môn học
                                </button>
                                <a href="index.php?controller=cauhoi" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Kiểm tra form khi submit
        document.querySelector('form').addEventListener('submit', function(event) {
            const namHocInput = document.getElementById('nam_hoc');
            const namHocValue = namHocInput.value.trim();
            
            // Kiểm tra nếu năm học có giá trị và có chứa ký tự không phải số hoặc dấu gạch ngang
            if (namHocValue !== '' && !/^[0-9-]+$/.test(namHocValue)) {
                alert('Năm học chỉ được nhập số và dấu gạch ngang (ví dụ: 2023-2024)');
                namHocInput.focus();
                event.preventDefault();
                return false;
            }
            
            return true;
        });
        
        // Format năm học khi input mất focus
        document.getElementById('nam_hoc').addEventListener('blur', function() {
            // Nếu đã nhập một năm (4 chữ số) và chưa có dấu gạch ngang, tự động thêm năm tiếp theo
            if (this.value.trim().length === 4 && !this.value.includes('-') && /^\d{4}$/.test(this.value)) {
                const namHienTai = parseInt(this.value);
                this.value = namHienTai + '-' + (namHienTai + 1);
            }
        });
    </script>
</body>
</html>
