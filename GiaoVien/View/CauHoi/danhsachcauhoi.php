<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Câu hỏi chủ đề <?= htmlspecialchars($chuDe['ten_chu_de']) ?></title>
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
        .question-box {
            background-color: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #0d6efd;
            transition: all 0.3s;
        }
        .question-box:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background-color: #e6f2ff;
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
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?controller=cauhoi">Danh sách môn học</a></li>
                        <li class="breadcrumb-item"><a href="index.php?controller=cauhoi&action=viewBySubject&id=<?= $monHoc['id'] ?>">
                            Chủ đề môn <?= htmlspecialchars($monHoc['ten_mon']) ?>
                        </a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Câu hỏi chủ đề <?= htmlspecialchars($chuDe['ten_chu_de']) ?>
                        </li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Câu hỏi chủ đề: <?= htmlspecialchars($chuDe['ten_chu_de']) ?></h2>
                    <div>
                          <!-- <a href="index.php?controller=cauhoi&action=create" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tạo câu hỏi mới
                    </a> -->
                        <a href="index.php?controller=cauhoi&action=create&mon_hoc_id=<?= $monHoc['id'] ?>&chu_de_id=<?= $chuDe['id'] ?>" 
                           class="btn btn-success">
                            <i class="fas fa-plus"></i> Tạo câu hỏi mới
                        </a> 
                    </div>
                </div>
                
                <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        Xóa câu hỏi thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['success']) && $_GET['success'] === 'created'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        Tạo câu hỏi thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php 
                        $error = $_GET['error'];
                        switch($error) {
                            case 'delete_failed':
                                echo 'Xóa câu hỏi thất bại!';
                                break;
                            case 'permission_denied':
                                echo 'Bạn không có quyền thực hiện thao tác này!';
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
                        <h5 class="mb-0">
                            <i class="fas fa-question-circle me-2"></i>
                            Danh sách câu hỏi chủ đề <?= htmlspecialchars($chuDe['ten_chu_de']) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($danhSachCauHoi)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>Chưa có câu hỏi nào cho chủ đề này.
                            </div>
                        <?php else: ?>
                            <?php foreach ($danhSachCauHoi as $index => $cauHoi): ?>
                                <div class="question-box">
                                    <div class="d-flex justify-content-between">
                                        <h5>Câu <?= $index + 1 ?>: <?= htmlspecialchars($cauHoi['noi_dung']) ?></h5>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($cauHoi['tieu_de']) ?></span>
                                    </div>
                                    <div class="mt-3">
                                        <a href="index.php?controller=cauhoi&action=view&id=<?= $cauHoi['id'] ?>&question_number=<?= $index + 1 ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye me-1"></i> Xem chi tiết
                                        </a>
                                        <a href="index.php?controller=cauhoi&action=edit&id=<?= $cauHoi['id'] ?>" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit me-1"></i> Sửa
                                        </a>
                                        <a href="index.php?controller=cauhoi&action=delete&id=<?= $cauHoi['id'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Bạn có chắc muốn xóa câu hỏi này?')">
                                            <i class="fas fa-trash me-1"></i> Xóa
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
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
