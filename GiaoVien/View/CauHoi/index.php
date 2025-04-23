<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý câu hỏi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="Assets/css/menu.css">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Quản lý câu hỏi theo môn học</h2>
                    <!-- <a href="index.php?controller=cauhoi&action=create" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tạo câu hỏi mới
                    </a> -->
                    <a href="index.php?controller=cauhoi&action=createSubject" class="btn btn-success">
                                <i class="fas fa-plus"></i> Thêm môn học mới
                    </a>
                </div>
                
                <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        Xóa câu hỏi thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['success']) && $_GET['success'] === 'subject_deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        Xóa môn học thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['success']) && $_GET['success'] === 'topic_deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        Xóa chủ đề thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php 
                        $error = $_GET['error'];
                        switch($error) {
                            case 'invalid_id':
                                echo 'ID câu hỏi không hợp lệ!';
                                break;
                            case 'not_found':
                                echo 'Không tìm thấy câu hỏi!';
                                break;
                            case 'subject_not_found':
                                echo 'Không tìm thấy môn học!';
                                break;
                            case 'topic_not_found':
                                echo 'Không tìm thấy chủ đề!';
                                break;
                            case 'permission_denied':
                                echo 'Bạn không có quyền xóa câu hỏi này!';
                                break;
                            case 'delete_failed':
                                echo 'Xóa câu hỏi thất bại!';
                                break;
                            case 'subject_has_topics':
                                echo 'Không thể xóa môn học vì vẫn còn chủ đề thuộc môn học này!';
                                break;
                            default:
                                echo 'Đã xảy ra lỗi!';
                        }
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-book me-2"></i>Danh sách môn học của tôi</h5>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($danhSachMonHoc)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>Chưa có môn học nào trong hệ thống.
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($danhSachMonHoc as $monHoc): ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card subject-card h-100">
                                            <div class="card-body text-center">
                                                <div class="subject-info">
                                                    <div class="subject-icon">
                                                        <i class="fas fa-book-open"></i>
                                                    </div>
                                                    <h5 class="card-title"><?= htmlspecialchars($monHoc['ten_mon']) ?></h5>
                                                    <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($monHoc['ma_mon']) ?></h6>
                                                    <p class="card-text">
                                                        <?= !empty($monHoc['mo_ta']) ? htmlspecialchars($monHoc['mo_ta']) : 'Không có mô tả' ?>
                                                    </p>
                                                    <div class="small text-muted mb-2">
                                                        <span class="badge bg-secondary">Học kỳ: <?= htmlspecialchars($monHoc['hoc_ky']) ?></span>
                                                        <span class="badge bg-info">Năm học: <?= htmlspecialchars($monHoc['nam_hoc']) ?></span>
                                                        <span class="badge bg-primary"><?= $monHoc['so_tin_chi'] ?> tín chỉ</span>
                                                    </div>
                                                </div>
                                                <div class="subject-actions">
                                                    <a href="index.php?controller=cauhoi&action=viewBySubject&id=<?= $monHoc['id'] ?>" 
                                                       class="btn btn-primary btn-sm w-100 mb-2">
                                                        <i class="fas fa-list me-1"></i> Xem chủ đề
                                                    </a>
                                                    <a href="index.php?controller=cauhoi&action=deleteSubject&id=<?= $monHoc['id'] ?>" 
                                                       class="btn btn-danger btn-sm w-100"
                                                       onclick="return confirm('Bạn có chắc muốn xóa môn học này?')">
                                                        <i class="fas fa-trash me-1"></i> Xóa môn học
                                                    </a>
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
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tự động ẩn thông báo thành công sau 4 giây
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-success.alert-dismissible');
            
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
    </script>
</body>
</html> 