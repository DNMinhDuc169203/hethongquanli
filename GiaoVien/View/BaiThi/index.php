<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bài thi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
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
        .badge {
            font-weight: 500;
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
                    <a href="index.php?controller=auth&action=logout" class="btn btn-danger w-100">
                    Đăng xuất
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Danh sách bài thi</h2>
                    <div class="btn-group">
                        <a href="index.php?controller=baithi&action=create" class="btn btn-success" style="margin-right: 10px;">Tạo bài thi trắc nghiệm</a>
                        <a href="index.php?controller=baithituluan&action=create" class="btn btn-success">Tạo bài thi tự luận</a>
                    </div>
                </div>
                
                <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        Xóa bài thi thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php 
                        switch($_GET['error']) {
                            case 'not_found':
                                echo 'Không tìm thấy bài thi!';
                                break;
                            case 'permission_denied':
                                echo 'Bạn không có quyền thực hiện thao tác này!';
                                break;
                            case 'delete_failed':
                                echo 'Xóa bài thi thất bại!';
                                break;
                            default:
                                echo 'Đã xảy ra lỗi!';
                        }
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h4>Bài thi trắc nghiệm</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Tiêu đề</th>
                                                <th>Môn học</th>
                                                <th>Lớp</th>
                                                <th>Thời gian</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($danhSachBaiThiTracNghiem)): ?>
                                                <?php foreach ($danhSachBaiThiTracNghiem as $baiThi): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($baiThi['tieu_de']); ?></td>
                                                        <td><?= htmlspecialchars($baiThi['ma_mon'] . ' - ' . $baiThi['ten_mon']); ?></td>
                                                        <td><?= htmlspecialchars($baiThi['ma_lop'] . ' - ' . $baiThi['ten_lop']); ?></td>
                                                        <td><?= $baiThi['thoi_gian_lam'] ? $baiThi['thoi_gian_lam'].' phút' : 'Không giới hạn'; ?></td>
                                                        
                                                        <td>
                                                            <div class="btn-group">
                                                                <a href="index.php?controller=baithi&action=view&id=<?= $baiThi['id']; ?>" 
                                                                   class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="index.php?controller=baithi&action=edit&id=<?= $baiThi['id']; ?>" 
                                                                   class="btn btn-sm btn-warning">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="index.php?controller=baithi&action=delete&id=<?= $baiThi['id']; ?>" 
                                                                   class="btn btn-sm btn-danger"
                                                                   onclick="return confirm('Bạn có chắc muốn xóa bài thi này?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">Không có bài thi trắc nghiệm nào.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h4>Bài thi tự luận</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Tiêu đề</th>
                                                <th>Môn học</th>
                                                <th>Lớp</th>
                                                <th>Thời gian</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($danhSachBaiThiTuLuan)): ?>
                                                <?php foreach ($danhSachBaiThiTuLuan as $baiThi): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($baiThi['tieu_de']); ?></td>
                                                        <td><?= htmlspecialchars($baiThi['ma_mon'] . ' - ' . $baiThi['ten_mon']); ?></td>
                                                        <td><?= htmlspecialchars($baiThi['ma_lop'] . ' - ' . $baiThi['ten_lop']); ?></td>
                                                        <td><?= $baiThi['thoi_gian_lam'] ? $baiThi['thoi_gian_lam'].' phút' : 'Không giới hạn'; ?></td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <a href="index.php?controller=baithituluan&action=view&id=<?= $baiThi['id']; ?>" 
                                                                   class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="index.php?controller=baithituluan&action=edit&id=<?= $baiThi['id']; ?>" 
                                                                   class="btn btn-sm btn-warning">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="index.php?controller=baithituluan&action=delete&id=<?= $baiThi['id']; ?>" 
                                                                   class="btn btn-sm btn-danger"
                                                                   onclick="return confirm('Bạn có chắc muốn xóa bài thi này?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">Không có bài thi tự luận nào.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto hide alerts after 4 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 4000);
        });
    </script>
</body>
</html> 