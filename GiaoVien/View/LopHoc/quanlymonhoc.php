<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý môn học - <?= htmlspecialchars($lopHoc['ten_lop']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid #0d6efd;
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
        .subject-item {
            transition: all 0.3s;
        }
        .subject-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
                        <li class="breadcrumb-item"><a href="index.php?controller=lophoc&action=view&id=<?= $lopHoc['id'] ?>">Chi tiết lớp học</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quản lý môn học</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Quản lý môn học - <?= htmlspecialchars($lopHoc['ten_lop']) ?></h2>
                    <a href="index.php?controller=lophoc&action=view&id=<?= $lopHoc['id'] ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $success ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['success']) && $_GET['success'] == 'removed'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        Đã xóa môn học khỏi lớp thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="row">
                    <!-- Danh sách môn học đã thêm -->
                    <div class="col-md-7">
                        <div class="card detail-card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Môn học đã thêm vào lớp</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($danhSachMonHoc)): ?>
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-book"></i>
                                        </div>
                                        <h5>Chưa có môn học nào trong lớp này</h5>
                                        <p>Thêm môn học để bắt đầu tạo bài thi và câu hỏi.</p>
                                    </div>
                                <?php else: ?>
                                    <ul class="subject-list">
                                        <?php foreach ($danhSachMonHoc as $monHoc): ?>
                                            <li class="subject-item">
                                                <div>
                                                    <span class="badge bg-secondary"><?= htmlspecialchars($monHoc['ma_mon']) ?></span>
                                                    <strong><?= htmlspecialchars($monHoc['ten_mon']) ?></strong>
                                                    <?php if (!empty($monHoc['mo_ta'])): ?>
                                                        <br>
                                                        <small class="text-muted"><?= htmlspecialchars($monHoc['mo_ta']) ?></small>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <a href="index.php?controller=lophoc&action=removeSubject&lop_hoc_id=<?= $lopHoc['id'] ?>&mon_hoc_id=<?= $monHoc['id'] ?>" 
                                                       class="btn btn-sm btn-outline-danger"
                                                       onclick="return confirm('Bạn có chắc muốn xóa môn học này khỏi lớp?')">
                                                        <i class="fas fa-trash"></i> Xóa
                                                    </a>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form thêm môn học -->
                    <div class="col-md-5">
                        <div class="card detail-card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Thêm môn học vào lớp</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($danhSachMonHocKhaDung)): ?>
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </div>
                                        <h5>Không có môn học khả dụng</h5>
                                        <p>Tất cả môn học đã được thêm vào lớp hoặc chưa có môn học nào được tạo.</p>
                                        <a href="index.php?controller=cauhoi&action=createSubject" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus"></i> Tạo môn học mới
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <form action="index.php?controller=lophoc&action=manageSubjects&id=<?= $lopHoc['id'] ?>" method="POST">
                                        <div class="mb-3">
                                            <label for="mon_hoc_id" class="form-label">Chọn môn học</label>
                                            <select class="form-select" name="mon_hoc_id" id="mon_hoc_id" required>
                                                <option value="">-- Chọn môn học --</option>
                                                <?php foreach ($danhSachMonHocKhaDung as $monHoc): ?>
                                                    <option value="<?= $monHoc['id'] ?>">
                                                        <?= htmlspecialchars($monHoc['ma_mon']) ?> - <?= htmlspecialchars($monHoc['ten_mon']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="form-text">Chọn môn học để thêm vào lớp.</div>
                                        </div>
                                        
                                        <div class="text-center">
                                            <button type="submit" name="add_mon_hoc" value="1" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Thêm môn học
                                            </button>
                                        </div>
                                    </form>
                                    
                                    <!-- <div class="mt-4">
                                        <a href="index.php?controller=cauhoi&action=createSubject" class="btn btn-outline-secondary w-100">
                                            <i class="fas fa-book"></i> Tạo môn học mới
                                        </a>
                                    </div> -->
                                <?php endif; ?>
                            </div>
                        </div>
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