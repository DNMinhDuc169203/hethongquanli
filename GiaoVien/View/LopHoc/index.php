<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý lớp học</title>
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
        .class-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .class-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-body {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .subject-badge {
            margin-right: 5px;
            margin-bottom: 5px;
            font-size: 0.8em;
        }
        .class-footer {
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .empty-state {
            text-align: center;
            padding: 50px 20px;
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Danh sách lớp học</h2>
                    <a href="index.php?controller=lophoc&action=create" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tạo lớp học mới
                    </a>
                </div>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php if ($_GET['success'] == 'created'): ?>
                            Tạo lớp học mới thành công!
                        <?php elseif ($_GET['success'] == 'deleted'): ?>
                            Xóa lớp học thành công!
                        <?php else: ?>
                            Thao tác thành công!
                        <?php endif; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php 
                        switch($_GET['error']) {
                            case 'not_found':
                                echo 'Không tìm thấy lớp học!';
                                break;
                            case 'permission_denied':
                                echo 'Bạn không có quyền thực hiện thao tác này!';
                                break;
                            case 'delete_failed':
                                echo 'Xóa lớp học thất bại!';
                                break;
                            case 'class_has_students':
                                echo 'Không thể xóa lớp học vì vẫn còn sinh viên trong lớp!';
                                break;
                            default:
                                echo 'Đã xảy ra lỗi!';
                        }
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($danhSachLopHoc)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <h4>Chưa có lớp học nào</h4>
                        <p>Bạn chưa tạo lớp học nào. Hãy bắt đầu bằng cách tạo lớp học mới.</p>
                        <!-- <a href="index.php?controller=lophoc&action=create" class="btn btn-primary mt-3">
                            <i class="fas fa-plus"></i> Tạo lớp học mới
                        </a> -->
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($danhSachLopHoc as $lopHoc): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card class-card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($lopHoc['ten_lop']) ?></h5>
                                        <h6 class="card-subtitle mb-2 text-muted">Mã lớp: <?= htmlspecialchars($lopHoc['ma_lop']) ?></h6>
                                        
                                        <p class="card-text">
                                            <?= !empty($lopHoc['mo_ta']) ? htmlspecialchars($lopHoc['mo_ta']) : 'Không có mô tả' ?>
                                        </p>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">Thời gian học:</small><br>
                                            <?php if (!empty($lopHoc['ngay_bat_dau']) && $lopHoc['ngay_bat_dau'] != '0000-00-00'): ?>
                                                <span class="badge bg-info"><?= date('d/m/Y', strtotime($lopHoc['ngay_bat_dau'])) ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($lopHoc['ngay_ket_thuc']) && $lopHoc['ngay_ket_thuc'] != '0000-00-00'): ?>
                                                <span>đến</span>
                                                <span class="badge bg-info"><?= date('d/m/Y', strtotime($lopHoc['ngay_ket_thuc'])) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between">
                                                <span><i class="fas fa-book"></i> <?= (int)$lopHoc['so_mon_hoc'] ?> môn học</span>
                                                <span><i class="fas fa-user-graduate"></i> <?= (int)$lopHoc['so_sinh_vien'] ?> học viên</span>
                                            </div>
                                        </div>
                                        
                                        <div class="class-footer">
                                            <div class="btn-group w-100">
                                                <a href="index.php?controller=lophoc&action=view&id=<?= $lopHoc['id'] ?>" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Chi tiết
                                                </a>
                                                <a href="index.php?controller=lophoc&action=manageSubjects&id=<?= $lopHoc['id'] ?>" class="btn btn-outline-success">
                                                    <i class="fas fa-book"></i> Môn học
                                                </a>
                                                <a href="index.php?controller=lophoc&action=delete&id=<?= $lopHoc['id'] ?>" 
                                                   class="btn btn-outline-danger"
                                                   onclick="return confirm('Bạn có chắc muốn xóa lớp học này?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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
