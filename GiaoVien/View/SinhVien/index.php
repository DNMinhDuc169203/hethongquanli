<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sinh viên - Lớp học của tôi</title>
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
                <h3>Quản lý sinh viên</h3>
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
                <h2 class="mb-4">Danh sách sinh viên</h2>
                <!-- <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Hiển thị các lớp học do giáo viên <strong><?php echo htmlspecialchars($ten_giao_vien); ?></strong> quản lý
                </div> -->
                
                <?php if (count($danhSachLopHoc) == 0): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> Không tìm thấy lớp học nào do bạn quản lý.
                </div>
                <?php endif; ?>
                
                <?php foreach ($danhSachLopHoc as $lopHoc): ?>
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Lớp: <?php echo htmlspecialchars($lopHoc['ten_lop']); ?></h5>
                        <span class="badge bg-light text-dark">
                            Số sinh viên: <?php echo count($lopHoc['sinh_vien']); ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>MSSV</th>
                                        <th>Họ và tên</th>
                                        <th>Email</th>
                                        <th>Năm nhập học</th>
                                        <th>Ngành học</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($lopHoc['sinh_vien']) == 0): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Chưa có sinh viên trong lớp này</td>
                                    </tr>
                                    <?php else: ?>
                                    <?php foreach ($lopHoc['sinh_vien'] as $sinhVien): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($sinhVien['ma_so']); ?></td>
                                        <td><?php echo htmlspecialchars($sinhVien['ho_va_ten']); ?></td>
                                        <td><?php echo htmlspecialchars($sinhVien['email']); ?></td>
                                        <td><?php echo htmlspecialchars($sinhVien['nam_nhap_hoc']); ?></td>
                                        <td><?php echo htmlspecialchars($sinhVien['nganh_hoc']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
