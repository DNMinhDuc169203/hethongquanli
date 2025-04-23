<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem bài thi tự luận</title>
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
                <h3>Quản lý kết quả bài thi</h3>
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
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?controller=ketquabaithi">Quản lý kết quả bài thi</a></li>
                        <li class="breadcrumb-item"><a href="index.php?controller=ketquabaithi&action=ketQuaTuLuan&id=<?= $baiKiemTra['id']; ?>">Kết quả bài thi tự luận</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bài làm của sinh viên</li>
                    </ol>
                </nav>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['success']; ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error']; ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title mb-0">
                                    Bài làm của <?= htmlspecialchars($sinhVien['ho_va_ten'] . ' (' . $sinhVien['ma_so'] . ')'); ?>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h5>Thông tin bài thi</h5>
                                    <p><strong>Tiêu đề:</strong> <?= htmlspecialchars($baiKiemTra['tieu_de']); ?></p>
                                    <p><strong>Môn học:</strong> <?= htmlspecialchars($baiKiemTra['ma_mon'] . ' - ' . $baiKiemTra['ten_mon']); ?></p>
                                    <p><strong>Thời gian nộp:</strong> <?= date('d/m/Y H:i:s', strtotime($baiLam['ngay_nop'])); ?></p>
                                </div>
                                
                                <div class="mb-3">
                                    <h5>Đề bài</h5>
                                    <div class="border p-3 bg-light">
                                        <?= $baiKiemTra['noi_dung']; ?>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <h5>Bài làm của sinh viên</h5>
                                    <div class="border p-3">
                                        <?= nl2br(htmlspecialchars($baiLam['noi_dung'])); ?>
                                    </div>
                                    
                                    <?php if (!empty($baiLam['tep_tin'])): ?>
                                        <div class="mt-2">
                                            <p><strong>Tệp đính kèm:</strong> <a href="<?= $baiLam['tep_tin']; ?>" target="_blank" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-download"></i> Tải xuống <?= basename($baiLam['tep_tin']); ?>
                                            </a></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h4 class="mb-0">Chấm điểm</h4>
                            </div>
                            <div class="card-body">
                                <form action="index.php?controller=ketquabaithi&action=chamDiem" method="POST">
                                    <input type="hidden" name="bai_lam_id" value="<?= $baiLam['id']; ?>">
                                    
                                    <div class="mb-3">
                                        <label for="diem" class="form-label">Điểm (0-10):</label>
                                        <input type="number" class="form-control" id="diem" name="diem" 
                                               min="0" max="10" step="0.1" 
                                               value="<?= $baiLam['diem'] !== null ? number_format($baiLam['diem'], 1) : ''; ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="nhan_xet" class="form-label">Nhận xét:</label>
                                        <textarea class="form-control" id="nhan_xet" name="nhan_xet" rows="8"><?= htmlspecialchars($baiLam['nhan_xet'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Lưu điểm và nhận xét
                                        </button>
                                        
                                        <a href="index.php?controller=ketquabaithi&action=ketQuaTuLuan&id=<?= $baiKiemTra['id']; ?>" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <?php if ($baiLam['diem'] !== null): ?>
                        <div class="card mt-3">
                            <div class="card-header bg-info text-white">
                                <h4 class="mb-0">Kết quả</h4>
                            </div>
                            <div class="card-body">
                                <h5>Điểm: <span class="badge <?= ($baiLam['diem'] >= 5) ? 'bg-success' : 'bg-danger'; ?>"><?= number_format($baiLam['diem'], 1); ?></span></h5>
                                <!-- <p><strong>Trạng thái:</strong> <?= ($baiLam['diem'] >= 5) ? '<span class="text-success">Đạt</span>' : '<span class="text-danger">Chưa đạt</span>'; ?></p> -->
                                <p><strong>Người chấm:</strong> <?= htmlspecialchars($_SESSION['ho_va_ten'] ?? 'Giáo viên'); ?></p>
                                <p><strong>Thời gian cập nhật:</strong> <?= date('d/m/Y H:i:s', strtotime($baiLam['ngay_cap_nhat'])); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html> 