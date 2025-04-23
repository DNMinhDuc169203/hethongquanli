<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết lớp học</title>
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
        .detail-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .subject-list {
            list-style: none;
            padding: 0;
        }
        .subject-list li {
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
            background-color: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .action-button {
            margin-left: 10px;
        }
        .empty-state {
            text-align: center;
            padding: 30px 20px;
        }
        .empty-state-icon {
            font-size: 3rem;
            color: #ccc;
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
                <h3>Quản lý lớp học</h3>
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
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?controller=lophoc">Danh sách lớp học</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết lớp học</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Chi tiết lớp học</h2>
                    <div>
                        <a href="index.php?controller=lophoc&action=edit&id=<?= $lopHoc['id'] ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <a href="index.php?controller=lophoc" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                
                <?php if (isset($_GET['success']) && $_GET['success'] == 'updated'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        Cập nhật thông tin lớp học thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card detail-card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Thông tin lớp học</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Mã lớp:</strong> <?= htmlspecialchars($lopHoc['ma_lop']) ?></p>
                                <p><strong>Tên lớp:</strong> <?= htmlspecialchars($lopHoc['ten_lop']) ?></p>
                                <p><strong>Mô tả:</strong> <?= !empty($lopHoc['mo_ta']) ? htmlspecialchars($lopHoc['mo_ta']) : 'Không có mô tả' ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Ngày bắt đầu:</strong> 
                                    <?= (!empty($lopHoc['ngay_bat_dau']) && $lopHoc['ngay_bat_dau'] != '0000-00-00') 
                                        ? date('d/m/Y', strtotime($lopHoc['ngay_bat_dau'])) 
                                        : 'Chưa thiết lập' ?>
                                </p>
                                <p><strong>Ngày kết thúc:</strong> 
                                    <?= (!empty($lopHoc['ngay_ket_thuc']) && $lopHoc['ngay_ket_thuc'] != '0000-00-00') 
                                        ? date('d/m/Y', strtotime($lopHoc['ngay_ket_thuc'])) 
                                        : 'Chưa thiết lập' ?>
                                </p>
                                <p><strong>Ngày tạo:</strong> <?= date('d/m/Y H:i', strtotime($lopHoc['ngay_tao'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card detail-card">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách môn học</h5>
                        <a href="index.php?controller=lophoc&action=manageSubjects&id=<?= $lopHoc['id'] ?>" class="btn btn-sm btn-light">
                            <i class="fas fa-plus"></i> Quản lý môn học
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($danhSachMonHoc)): ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <h5>Chưa có môn học nào trong lớp này</h5>
                                <p>Thêm môn học vào lớp để bắt đầu tạo bài thi, câu hỏi.</p>
                                <a href="index.php?controller=lophoc&action=manageSubjects&id=<?= $lopHoc['id'] ?>" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Thêm môn học
                                </a>
                            </div>
                        <?php else: ?>
                            <ul class="subject-list">
                                <?php foreach ($danhSachMonHoc as $monHoc): ?>
                                    <li>
                                        <div>
                                            <span class="badge bg-secondary"><?= htmlspecialchars($monHoc['ma_mon']) ?></span>
                                            <strong><?= htmlspecialchars($monHoc['ten_mon']) ?></strong>
                                            <?php if (!empty($monHoc['mo_ta'])): ?>
                                                <small class="text-muted">- <?= htmlspecialchars($monHoc['mo_ta']) ?></small>
                                            <?php endif; ?>
                                            <br>
                                            <small class="text-muted">Giáo viên: <?= htmlspecialchars($monHoc['ten_giao_vien'] ?? 'Chưa có thông tin') ?></small>
                                        </div>
                                        <!-- <   div class="d-flex">
                                            <a href="index.php?controller=baithi&action=create&lop_hoc_id=<?= $lopHoc['id'] ?>&mon_hoc_id=<?= $monHoc['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary action-button">
                                                <i class="fas fa-file-alt"></i> Tạo bài thi
                                            </a>
                                            <a href="index.php?controller=cauhoi&action=create&mon_hoc_id=<?= $monHoc['id'] ?>" 
                                               class="btn btn-sm btn-outline-success action-button">
                                                <i class="fas fa-question-circle"></i> Tạo câu hỏi
                                            </a>
                                        </div> -->
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="index.php?controller=lophoc&action=delete&id=<?= $lopHoc['id'] ?>" 
                       class="btn btn-danger"
                       onclick="return confirm('Bạn có chắc muốn xóa lớp học này? Tất cả dữ liệu liên quan sẽ bị mất.')">
                        <i class="fas fa-trash"></i> Xóa lớp học
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tự động ẩn thông báo thành công sau 4 giây
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-success.alert-dismissible');
            
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    alert.classList.remove('show');
                    setTimeout(function() {
                        bsAlert.close();
                    }, 300);
                }, 4000);
            });
        });
    </script>
</body>
</html> 