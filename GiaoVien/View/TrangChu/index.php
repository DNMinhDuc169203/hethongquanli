<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang tổng quan giáo viên - Hệ thống quản lý môn học</title>
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
        .stat-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
        }
        .stat-label {
            font-size: 1rem;
            color: #6c757d;
        }
        .recent-card {
            transition: transform 0.3s;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .recent-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .welcome-section {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .welcome-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .welcome-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .profile-card {
            padding: 20px;
            border-radius: 15px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .badge-custom {
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 20px;
        }
        .nav-tabs .nav-item .nav-link {
            color: #495057;
            background-color: #f8f9fa;
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
            font-weight: 500;
        }
        .nav-tabs .nav-item .nav-link.active {
            color: #007bff;
            background-color: #fff;
            border-bottom: 2px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h3>Quản lý học tập</h3>
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
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <div class="welcome-title">Xin chào, <?= htmlspecialchars($giaoVienInfo['ho_va_ten'] ?? 'Giáo viên') ?></div>
                    <div class="welcome-subtitle">
                        <div class="mb-2">Mã số: <?= htmlspecialchars($giaoVienInfo['ma_so'] ?? '') ?></div>
                        <div>Học vị: <?= htmlspecialchars($giaoVienInfo['hoc_vi'] ?? '') ?> - Chuyên ngành: <?= htmlspecialchars($giaoVienInfo['chuyen_nganh'] ?? '') ?></div>
                    </div>
                </div>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card bg-primary text-white">
                            <div class="card-body text-center">
                                <div class="stat-icon">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div class="stat-value"><?= $totalLopHoc ?></div>
                                <div class="stat-label">Lớp học</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-success text-white">
                            <div class="card-body text-center">
                                <div class="stat-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div class="stat-value"><?= $totalSinhVien ?></div>
                                <div class="stat-label">Sinh viên</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-info text-white">
                            <div class="card-body text-center">
                                <div class="stat-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="stat-value"><?= $totalMonHoc ?></div>
                                <div class="stat-label">Môn học</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-warning text-white">
                            <div class="card-body text-center">
                                <div class="stat-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="stat-value"><?= $totalBaiThi ?></div>
                                <div class="stat-label">Bài thi</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="classes-tab" data-bs-toggle="tab" data-bs-target="#classes" type="button" role="tab" aria-controls="classes" aria-selected="true">
                            <i class="fas fa-school me-2"></i>Lớp học
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="subjects-tab" data-bs-toggle="tab" data-bs-target="#subjects" type="button" role="tab" aria-controls="subjects" aria-selected="false">
                            <i class="fas fa-book me-2"></i>Môn học
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="exams-tab" data-bs-toggle="tab" data-bs-target="#exams" type="button" role="tab" aria-controls="exams" aria-selected="false">
                            <i class="fas fa-file-alt me-2"></i>Bài kiểm tra
                        </button>
                    </li>
                </ul>
                
                <!-- Tab Content -->
                <div class="tab-content" id="myTabContent">
                    <!-- Classes Tab -->
                    <div class="tab-pane fade show active" id="classes" role="tabpanel" aria-labelledby="classes-tab">
                        <div class="card">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-school me-2"></i>Danh sách lớp học của bạn
                                    </h5>
                                    <!-- <a href="index.php?controller=lophoc&action=create" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Tạo lớp mới
                                    </a> -->
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (empty($lopHocMoi)): ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-info-circle text-muted" style="font-size: 3rem;"></i>
                                        <p class="mt-3">Bạn chưa có lớp học nào.</p>
                                        <!-- <a href="index.php?controller=lophoc&action=create" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tạo lớp học mới
                                        </a> -->
                                    </div>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($lopHocMoi as $lop): ?>
                                            <a href="index.php?controller=lophoc&action=view&id=<?= $lop['id'] ?>" 
                                               class="list-group-item list-group-item-action recent-card">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1"><?= htmlspecialchars($lop['ten_lop']) ?></h5>
                                                    <small class="text-muted">
                                                        <?= date('d/m/Y', strtotime($lop['ngay_tao'])) ?>
                                                    </small>
                                                </div>
                                                <p class="mb-1">Mã lớp: <span class="badge bg-secondary"><?= htmlspecialchars($lop['ma_lop']) ?></span></p>
                                                <small>
                                                    <i class="fas fa-book"></i> <?= (int)$lop['so_mon_hoc'] ?> môn học
                                                    <span class="mx-2">|</span>
                                                    <i class="fas fa-user-graduate"></i> <?= (int)$lop['so_sinh_vien'] ?> sinh viên
                                                </small>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer text-center">
                                <a href="index.php?controller=lophoc" class="btn btn-outline-primary">
                                    Xem tất cả lớp học <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subjects Tab -->
                    <div class="tab-pane fade" id="subjects" role="tabpanel" aria-labelledby="subjects-tab">
                        <div class="card">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-book me-2"></i>Danh sách môn học của bạn
                                    </h5>
                                    <!-- <a href="index.php?controller=monhoc&action=create" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Thêm môn học
                                    </a> -->
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (empty($monHocList)): ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-info-circle text-muted" style="font-size: 3rem;"></i>
                                        <p class="mt-3">Bạn chưa có môn học nào.</p>
                                        <!-- <a href="index.php?controller=monhoc&action=create" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Thêm môn học mới
                                        </a> -->
                                    </div>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($monHocList as $monHoc): ?>
                                            <a href="index.php?controller=monhoc&action=view&id=<?= $monHoc['id'] ?>" 
                                               class="list-group-item list-group-item-action recent-card">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1"><?= htmlspecialchars($monHoc['ten_mon']) ?></h5>
                                                    <small class="text-muted">
                                                        <?= date('d/m/Y', strtotime($monHoc['ngay_tao'])) ?>
                                                    </small>
                                                </div>
                                                <p class="mb-1">
                                                    <span class="badge bg-primary"><?= htmlspecialchars($monHoc['ma_mon']) ?></span>
                                                    <span class="badge bg-info">Học kỳ: <?= htmlspecialchars($monHoc['hoc_ky']) ?></span>
                                                    <span class="badge bg-success"><?= (int)$monHoc['so_tin_chi'] ?> tín chỉ</span>
                                                </p>
                                                <small><?= $monHoc['mo_ta'] ? htmlspecialchars(substr($monHoc['mo_ta'], 0, 100)) . '...' : 'Không có mô tả' ?></small>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer text-center">
                                <a href="index.php?controller=monhoc" class="btn btn-outline-primary">
                                    Xem tất cả môn học <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Exams Tab -->
                    <div class="tab-pane fade" id="exams" role="tabpanel" aria-labelledby="exams-tab">
                        <div class="card">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-file-alt me-2"></i>Danh sách bài kiểm tra gần đây
                                    </h5>
                                    <!-- <div>
                                        <a href="index.php?controller=baithi&action=create&type=trac_nghiem" class="btn btn-primary btn-sm me-2">
                                            <i class="fas fa-plus"></i> Thêm bài trắc nghiệm
                                        </a>
                                        <a href="index.php?controller=baithi&action=create&type=tu_luan" class="btn btn-success btn-sm">
                                            <i class="fas fa-plus"></i> Thêm bài tự luận
                                        </a> -->
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (empty($baiKiemTraMoi)): ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-info-circle text-muted" style="font-size: 3rem;"></i>
                                        <p class="mt-3">Bạn chưa có bài kiểm tra nào.</p>
                                        <div>
                                            <!-- <a href="index.php?controller=baithi&action=create&type=trac_nghiem" class="btn btn-primary me-2">
                                                <i class="fas fa-plus"></i> Tạo bài trắc nghiệm
                                            </a>
                                            <a href="index.php?controller=baithi&action=create&type=tu_luan" class="btn btn-success">
                                                <i class="fas fa-plus"></i> Tạo bài tự luận
                                            </a> -->
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($baiKiemTraMoi as $baiThi): ?>
                                            <a href="index.php?controller=baithi&action=view&id=<?= $baiThi['id'] ?>&type=<?= $baiThi['loai'] ?>" 
                                               class="list-group-item list-group-item-action recent-card">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1"><?= htmlspecialchars($baiThi['tieu_de']) ?></h5>
                                                    <small class="text-muted">
                                                        <?= date('d/m/Y', strtotime($baiThi['ngay_tao'])) ?>
                                                    </small>
                                                </div>
                                                <p class="mb-1">
                                                    <span class="badge <?= $baiThi['loai'] == 'trac_nghiem' ? 'bg-primary' : 'bg-success' ?>">
                                                        <?= $baiThi['loai'] == 'trac_nghiem' ? 'Trắc nghiệm' : 'Tự luận' ?>
                                                    </span>
                                                </p>
                                                <small>
                                                    <i class="fas fa-school"></i> Lớp: <?= htmlspecialchars($baiThi['ten_lop'] ?? 'N/A') ?>
                                                    <span class="mx-2">|</span>
                                                    <i class="fas fa-book"></i> Môn: <?= htmlspecialchars($baiThi['ten_mon'] ?? 'N/A') ?>
                                                </small>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer text-center">
                                <a href="index.php?controller=baithi" class="btn btn-outline-primary">
                                    Xem tất cả bài kiểm tra <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 